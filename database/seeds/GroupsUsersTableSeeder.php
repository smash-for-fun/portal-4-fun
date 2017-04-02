<?php

use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;

class GroupsUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table(config('laravel-permission.table_names.user_has_groups'))->insert([
            'group_id' => 1,
            'user_id' => 1,
        ]);
    }
}
