<?php

namespace App\Account;


use App\User;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    const AllowedProviders = ['facebook', 'github', 'twitter', 'linkedin','google'];
    protected $fillable = ['user_id', 'provider_user_id', 'provider'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
