<?php

namespace App\Controller;

class User extends \Core\Controller
{
    public function UserAction()
    {
        $model = new \App\Models\User\userModel();

        if (!$model->UserAction()) {
            session_destroy();
            header('Location: /index');
            return;
        }

        $this->view->user = $model;

    }

    public function uploadAvatarAction()
    {
        $data['image'] = $_FILES['avatar'] ?? '';

        $model = new \App\Models\File\fileModel();
        if ($model->uploadAvatar($data)) {
            header('Location: /profile');
        }else{
            header('Location: /profile');
        }
    }

    public function avatarsAction()
    {
        if ($this->isSession()) {
            $model = new \App\Models\File\fileModel();
            $this->view->avatars = $model;
        }else{
            header('Location: /index');
        }
    }
    public function listUsersAction(){
        if ($this->isSession()) {
            $model = new \App\Models\User\userModel();

            $this->view->users = $model;
        }else{
            header('Location: /index');
        }
    }

    public function exitAction()
    {
        session_destroy();
        header('Location: /index');
    }

    public function deliteAction()
    {
        if ($this->isSession()) {
            $model = new \App\Models\User\userModel();
            $model->deliteAccount();
            header('Location: /index');
        }else{
            header('Location: /index');
        }
    }
}