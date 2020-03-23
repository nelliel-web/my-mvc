<?php

namespace App\Controller;

class Index extends \Core\Controller
{
    public function indexAction()
    {
        if ($this->isSession()) {
            header('Location: /profile');
            return;
        }
    }

    public function registerAction()
    {
        $model = new \App\Models\Index\indexModel();
        $data = $model->getFromPost($_POST);

        $model->loadData($data, true);
        if (!$model->check($error)) {
            $_SESSION['error'] = $error;
            header('Location: /index');
            return;
        }

        if ($model->save()) {
            header('Location: /profile');
            return;
        }

        header('Location: /index');

    }

    public function authAction()
    {
        if ($this->isSession()) {
            header('Location: /profile');
            return;
        }
        $model = new \App\Models\Index\indexModel();
        $data = $model->getFromPost($_POST, 'auth');

        if (!empty($data['email']) and !empty($data['pass'])) {
            if ($model->authUser($data) === false) {
                $_SESSION['error_auth'] = 'Неверный логин или пароль';
                return;
            }
            header('Location: /profile');
        }

    }
}