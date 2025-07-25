<?php

namespace App\Http\Services\V2;

use App\Enums\AccessLevel;
use App\Models\BranchOffice;
use App\Models\V2\Survey\Survey;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;


abstract class BaseService
{
    public function throttle(Request $request): void // maybe: move to middleware
    {
        if (config('app.debug')) {
            return;
        }

        $key = 'survey-create:' . (auth()->user()?->id ?: $request->ip());

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException(__('throttle.report-create'));
        }

        RateLimiter::hit($key, 60);
    }

    public function authorizeBranchAccess(Survey $survey): void
    {
        // todo: we have three methods of authorization in the app:
        // access levels (app, branch, etc.),
        // roles (branch_user, central_user, etc.), and
        // permissions (create, view, etc.)
        // on top of checking for branch office access.
        // We should unify this logic in the middleware using permissions.

        $user = auth()->user();

        switch ($user['access_level']) {
            case AccessLevel::APP:
                return;

            case AccessLevel::COMPANY:
                if ($survey->branch_office->rescue_company_id === $user->rescue_company_id) {
                    return;
                }
                break;

            case AccessLevel::BRANCH:
                if ($survey->branch_office_id === $user['branch_id']) {
                    return;
                }
                break;

            default:
                break;
        }

        abort(403, __('validation.survey.violations.create.unauthorized'));
    }
}
