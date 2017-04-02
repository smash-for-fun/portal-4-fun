<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\User::create([
            'name' => 'Glenn Latome',
            'email' => 'glenn.latomme@gmail.com',
            'password' => bcrypt('admin'),
        ]);
    }
}
