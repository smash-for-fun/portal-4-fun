<?php
/**
 * Created by PhpStorm.
 * User: Glenn Latomme
 * Date: 4/2/2017
 * Time: 2:26 PM
 */

namespace app\Models;
use Backpack\CRUD\CrudTrait;
use Spatie\Permission\Models\Permission as OriginalPermission;

class Permission extends OriginalPermission
{
    use CrudTrait;

    protected $fillable = ['name', 'updated_at', 'created_at'];
    protected $visible = ['name'];

    public function groups()
    {
        return $this->belongsToMany(config('auth.providers.groups.model'),
            config('laravel-permission.table_names.group_has_permissions')
        );
    }
}
