<?php
namespace App\Controller;

class Index extends \Core\Controller
{
    public function indexAction(){
        $model = new \App\Models\Index\indexModel();

        $this->view->userName = $model->getName();

    }
}