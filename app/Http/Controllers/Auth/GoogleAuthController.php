<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // User already registered with google, log them in
                Auth::login($user);
                return redirect()->intended(route('home', absolute: false));
            } else {
                // Check if user exists with the same email
                $existingUser = User::where('email', $googleUser->email)->first();
                
                if ($existingUser) {
                    // Google proves email ownership. If the local account was never verified,
                    // rotate its password so nobody who pre-registered this email keeps access.
                    $updates = ['google_id' => $googleUser->id];
                    if (is_null($existingUser->email_verified_at)) {
                        $updates['password'] = bcrypt(Str::random(32));
                        $updates['email_verified_at'] = now();
                    }
                    $existingUser->forceFill($updates)->save();
                    Auth::login($existingUser);
                } else {
                    // Create new user
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        // Password is nullable in DB, but we'll set a random string just in case
                        'password' => bcrypt(Str::random(24))
                    ]);
                    // Google has already verified this email address
                    $newUser->forceFill(['email_verified_at' => now()])->save();
                    Auth::login($newUser);
                }
                
                return redirect()->intended(route('home', absolute: false));
            }
        } catch (\Exception $e) {
            return redirect('/login')->with('error', __('فشل تسجيل الدخول باستخدام جوجل. يرجى المحاولة مرة أخرى.'));
        }
    }
}
