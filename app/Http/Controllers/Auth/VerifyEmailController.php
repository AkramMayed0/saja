<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $redirectUrl = $request->user()->is_admin ? route('admin.dashboard') : route('home');

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($redirectUrl . '?verified=1')->with('success', __('البريد الإلكتروني مفعل مسبقاً!'));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($redirectUrl . '?verified=1')->with('success', __('تم تأكيد وتفعيل بريدك الإلكتروني بنجاح! مرحباً بك في متجر مؤسسة سجى.'));
    }
}
