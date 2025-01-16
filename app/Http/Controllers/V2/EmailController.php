<?php

namespace App\Http\Controllers\V2;

use App\Mail\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'feedback' => 'required|string'
        ]);

        $user = auth()->user();

        $details = [
            'name' => $user->full_name,
            'email' => $user->email,
            'title' => $validated['title'],
            'feedback' => $validated['feedback']
        ];

        try {
            Mail::to(config('mail.to.address'))->send(new UserFeedback($details));
            } catch (\Exception $e) {
                Log::error($e->getMessage());

                return response()->json(['message' => __('etc.email.send.error')], 500);
            }

        return response()->json(['message' => __('etc.email.send.success')], 200);
    }
}
