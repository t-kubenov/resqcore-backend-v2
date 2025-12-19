<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserPermissionController extends Controller
{
    /**
     * @throws Throwable
     */
    public function sync(User $user, Request $request)
    {
        $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        DB::transaction(fn () =>
            $user->syncPermissions($request->permissions)
        );

        return response()->json([
            'message' => __('etc.permissions.sync_success')
        ]);
    }
}
