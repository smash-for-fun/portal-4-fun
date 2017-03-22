<?php

namespace App\Http\Controllers\Auth;


use App\Account\AccountService;
use App\Account\SocialAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;


class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        // check if valid provider
        if (!in_array($provider, SocialAccount::AllowedProviders)) {
            abort(403, 'Unauthorized action.');
        }
        return Socialite::driver($provider)->redirect();

    }

    public function callback($provider, Request $request, AccountService $service)
    {
        // check if valid provider
        if (!in_array($provider, SocialAccount::AllowedProviders)) {
            abort(403, 'Unauthorized action.');
        }
        // check if allowed access
        if ($request->error == 'access_denied') {
            return redirect('login');
        }

        $socialUser = Socialite::driver($provider)->user();
        $user = $service->createOrGetSocialUser($provider, $socialUser);

        auth()->login($user);
        return redirect()->to('/home');
    }
}
