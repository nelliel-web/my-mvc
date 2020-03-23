<?php

namespace App\Controller;

class Error404 extends \Core\Controller
{
    public function error404Action()
    {
        $error404 = new \App\Models\Error404\Error404Model();
    }
}