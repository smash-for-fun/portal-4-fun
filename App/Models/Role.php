<?php
/**
 * Created by PhpStorm.
 * User: Glenn Latomme
 * Date: 4/2/2017
 * Time: 2:29 PM
 */

namespace app\Models;
use Backpack\CRUD\CrudTrait;
use Spatie\Permission\Models\Role as OriginalRole;

class Role extends OriginalRole
{
    use CrudTrait;

    protected $fillable = ['name', 'updated_at', 'created_at'];
    protected $visible = ['name'];

    public function groups()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.group'),
            config('laravel-permission.table_names.group_has_roles')
        );
    }
}
