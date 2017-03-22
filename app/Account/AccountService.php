<?php
/**
 * Created by PhpStorm.
 * User: SIDGLAT
 * Date: 17/03/2017
 * Time: 16:02
 */

namespace App\Account;

use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;

class AccountService
{

    public function createOrGetEmailUser($email){
        $user = User::whereEmail($email)->first();
        if (!$user) {
            $user = User::create([
                'email' => $email,
                'name' => $email,
            ]);
        }
        return $user;
    }


    public function createOrGetSocialUser($provider, ProviderUser $providerUser)
    {
        $account = SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $provider
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {

                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;

        }

    }
}