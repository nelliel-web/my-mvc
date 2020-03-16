<?php
namespace App\Controller;

class Index extends \Core\Controller
{
    public function indexAction(){
        echo 'мы тут';
        $this->_render = false;
    }
}