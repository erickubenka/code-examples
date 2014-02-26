<?php

/**
 * Created by PhpStorm.
 * User: eric.kubenka
 * Date: 26.02.14
 * Time: 12:12
 */
class UserListViewModel
{
    public function load()
    {
        $dbusers = User::all();
        $users = [];

        foreach($dbusers as $user)
        {
            $usr = new UserModel();
            $usr->id = $user->id;
            $usr->email = $user->email;
            $usr->name = $user->name;

            $users[] = $usr;
        }

        return $users;
    }
} 