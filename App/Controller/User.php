<?php
namespace App\Controller;

class User extends \Core\Controller
{
    public function UserAction(){
        echo 'User';
        $this->_render = false;
    }
}