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

        $data['name'] = $_POST['name'] ?? '';
        $data['email'] = $_POST['email'] ?? '';
        $data['pass'] = $_POST['pass'] ?? '';
        $data['age'] = $_POST['age'] ?? '';
        $data['desc'] = $_POST['desc'] ?? '';
        $data['image'] = $_FILES['avatar'] ?? '';

        $model = new \App\Models\Index\indexModel();
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
        $data['email'] = $_POST['email'] ?? '';
        $data['pass'] = $_POST['pass'] ?? '';

        if (!empty($data['email']) and !empty($data['pass'])) {
            $model = new \App\Models\Index\indexModel();
            if ($model->authUser($data) === false) {
                $_SESSION['error_auth'] = 'Неверный логин или пароль';
                return;
            }
            header('Location: /profile');
        }


    }
}