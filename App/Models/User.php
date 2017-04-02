<?php

namespace  App\Models;

use app\Exceptions\UserDoesNotExist;
use App\Traits\HasRolesAndGroups;
use Backpack\CRUD\CrudTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use CrudTrait;
    use HasRolesAndGroups;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $visible = [
        'id',
        'name',
        'email',
        'groups',
        'roles',
        'permissions'
    ];

    /**
     * Find a user by its name.
     *
     * @param string $name
     *
     * @throws UserDoesNotExist
     *
     * @return user
     */
    public static function findByName($name)
    {
        $user = static::where('name', $name)->first();

        if (! $user) {
            throw new UserDoesNotExist();
        }

        return $user;
    }

    public static function findByEmail($email)
    {
        $user = static::where('email', $email)->first();

        if (! $user) {
            throw new UserDoesNotExist();
        }

        return $user;
    }
}
