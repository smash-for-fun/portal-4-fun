<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $config = config('laravel-permission.table_names');

        Schema::create($config['groups'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });



        Schema::create($config['user_has_groups'], function (Blueprint $table) use ($config) {
            $table->integer('group_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('group_id')
                ->references('id')
                ->on($config['groups'])
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on($config['users'])
                ->onDelete('cascade');

            $table->primary(['group_id', 'user_id']);

            Schema::create($config['group_has_permissions'], function (Blueprint $table) use ($config) {
                $table->integer('permission_id')->unsigned();
                $table->integer('group_id')->unsigned();

                $table->foreign('permission_id')
                    ->references('id')
                    ->on($config['permissions'])
                    ->onDelete('cascade');

                $table->foreign('group_id')
                    ->references('id')
                    ->on($config['groups'])
                    ->onDelete('cascade');

                $table->primary(['permission_id', 'group_id']);
            });

            Schema::create($config['group_has_roles'], function (Blueprint $table) use ($config) {
                $table->integer('role_id')->unsigned();
                $table->integer('group_id')->unsigned();

                $table->foreign('role_id')
                    ->references('id')
                    ->on($config['roles'])
                    ->onDelete('cascade');

                $table->foreign('group_id')
                    ->references('id')
                    ->on($config['groups'])
                    ->onDelete('cascade');

                $table->primary(['role_id', 'group_id']);
            });
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $config = config('laravel-permission.table_names');
        Schema::drop($config['group_has_permissions']);
        Schema::drop($config['group_has_roles']);
        Schema::drop($config['user_has_groups']);
        Schema::drop($config['groups']);
    }
}
