<?php

/**
 * Created by PhpStorm.
 * User: eric.kubenka
 * Date: 26.02.14
 * Time: 12:21
 */
class UserModel
{
    public $id;
    public $name;
    public $password;
    public $email;
    private $dbuser;

    public function load($id)
    {
        $this->dbuser = User::find($id);

        if(!is_null($this->dbuser))
        {
            $this->id = $this->dbuser->id;
            $this->name = $this->dbuser->name;
            $this->password = $this->dbuser->password;
            $this->email = $this->dbuser->email;

            return $this->dbuser;
        }

        return null;
    }

    public function save()
    {
        if(is_null($this->dbuser))
        {
            $this->dbuser = new User();
        }

        $this->dbuser->name = $this->name;
        $this->dbuser->password = $this->password;
        $this->dbuser->email = $this->email;

        $result = $this->dbuser->save();

        $this->id = $this->dbuser->id;
        return $result;
    }

    public function delete()
    {
        if(!is_null($this->dbuser)){
            $this->dbuser->delete();
        }
    }
} 