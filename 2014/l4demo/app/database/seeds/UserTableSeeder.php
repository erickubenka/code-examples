<?php

/**
 * Created by PhpStorm.
 * User: eric.kubenka
 * Date: 25.02.14
 * Time: 15:03
 */
class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();
        User::create(['name'    => 'Eric', 'email'   => 'eric@test.de', 'password' => Hash::make('password')]);
        User::create(['name'    => 'Rico', 'email'   => 'rico@test.de', 'password' => Hash::make('password')]);
        User::create(['name'    => 'Jan', 'email'   => 'jan@test.de', 'password' => Hash::make('password')]);
        User::create(['name'    => 'David', 'email'   => 'davidh@test.de', 'password' => Hash::make('password')]);
        User::create(['name'    => 'David', 'email'   => 'davida@test.de', 'password' => Hash::make('password')]);
        User::create(['name'    => 'Johannes', 'email'   => 'johannes@test.de', 'password' => Hash::make('password')]);
    }

} 