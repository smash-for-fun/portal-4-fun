<?php
/**
 * Created by PhpStorm.
 * User: Glenn Latomme
 * Date: 4/2/2017
 * Time: 4:30 PM
 */

namespace App\Traits;


use app\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use function MongoDB\BSON\toJSON;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\RefreshesPermissionCache;

trait HasRolesAndGroups
{
    use HasRoles;

    //region relations

    /**
     * A user may have multiple direct permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.group'),
            config('laravel-permission.table_names.user_has_groups')
        );
    }
    //endregion

    //region Group function
    /**
     * Scope the user query to certain groups only.
     *
     * @param string|array|Group|\Illuminate\Support\Collection $groups
     *
     * @return bool
     */
    public function scopeGroup($query, $groups)
    {
        if ($groups instanceof Collection) {
            $groups = $groups->toArray();
        }

        if (!is_array($groups)) {
            $groups = [$groups];
        }

        $groups = array_map(function ($group) {
            if ($group instanceof Group) {
                return $group;
            }

            return app(Group::class)->findByName($group);
        }, $groups);

        return $query->whereHas('groups', function ($query) use ($groups) {
            $query->where(function ($query) use ($groups) {
                foreach ($groups as $group) {
                    $query->orWhere(config('laravel-permission.table_names.groups') . '.id', $group->id);
                }
            });
        });
    }

    /**
     * Assign the given group to the user.
     *
     * @param array|string|\Spatie\Permission\Models\Group ...$groups
     *
     * @return \Spatie\Permission\Contracts\Group
     */
    public function assignGroup(...$groups)
    {
        $groups = collect($groups)
            ->flatten()
            ->map(function ($group) {
                return $this->getStoredGroup($group);
            })
            ->all();

        $this->groups()->saveMany($groups);

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Revoke the given group from the user.
     *
     * @param string|Group $group
     */
    public function removeGroup($group)
    {
        $this->groups()->detach($this->getStoredGroup($group));
    }

    /**
     * Remove all current groups and set the given ones.
     *
     * @param array ...$groups
     *
     * @return $this
     */
    public function syncGroups(...$groups)
    {
        $this->groups()->detach();

        return $this->assignGroup($groups);
    }
    //endregion

    //region Get Roles
    /**
     * Return all the permissions the user has via roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRolesViaGroups()
    {
        return $this->load('groups', 'groups.roles')
            ->groups->flatMap(function ($groups) {
                return $groups->roles;
            })->sort()->values();
    }

    /**
     * Return all the permissions the user has, both directly and via roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllRoles()
    {
        return $this->roles->merge($this->getRolesViaGroups())->sort()->values();
    }
    //endregion

    //region Get Permissions
    /**
     * Return all the permissions the user has via roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsViaGroups()
    {
        return $this->load('groups', 'groups.permissions')
            ->groups->flatMap(function ($groups) {
                return $groups->permissions;
            })->sort()->values();
    }

    /**
     * Return all the permissions the user has via roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsViaGroupsRoles()
    {
        return $this
            ->getAllRoles()
            ->flatMap(function ($group) {
                return $group->permissions;
            })->sort()->values();

    }

    /**
     * Return all the permissions the user has, both directly and via roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllPermissions()
    {
        return $this->permissions
            ->merge($this->getPermissionsViaRoles()
            ->merge($this->getPermissionsViaGroups())
            ->merge($this->getPermissionsViaGroupsRoles())
        )->sort()->values();
    }
    //endregion

    //region hasRole
    public function hasRole($roles)
    {
        $allRoles = $this->getAllRoles();
        if (is_string($roles)) {
            return $allRoles->contains('name', $roles);
        }

        if ($roles instanceof Role) {
            return $allRoles->contains('id', $roles->id);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }

            return false;
        }

        return (bool)$roles->intersect($allRoles)->count();
    }

    //endregion

    public function hasPermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = app(Permission::class)->findByName($permission);

            if (!$permission) {
                return false;
            }
        }
        return $this->getAllPermissions()->contains($permission);
    }
}