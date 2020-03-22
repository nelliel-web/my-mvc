<?php

namespace App\Models\File;

class fileModel
{

    private $_id;
    private $_image;


    private function setId()
    {
        $this->_id = $_SESSION['id'];
    }


    public function uploadAvatar(array $data)
    {
        $this->_image = $data['image'] ?? '';
        $this->setId();
        $path = ROOT . "uploads\\" . $this->_id . "\\";

        $typeFile = $this->checkFile();
        if (empty($typeFile)) {
            return false;
        }

        if (!is_dir($path)) {
            mkdir($path, 0700);
        }

        if (!move_uploaded_file($this->_image['tmp_name'],
            $path . basename($this->_image['tmp_name'], "tmp") . $typeFile)) {
            return false;
        }
        $imageName = sha1(mt_rand(1, 1000) . 'da0s' . $this->_image['tmp_name'] . mt_rand(1, 100)) . '.' . $typeFile;
        $avatarUrl = "uploads/" . $this->_id . "/" . $imageName;
        rename("uploads/" . $this->_id . "/" . basename($this->_image['tmp_name'], "tmp") . $typeFile,
            $avatarUrl);

        $this->_image = $avatarUrl;

        $db = \Core\Context::i()->getDb();
        $ret = $db->exec(
            "INSERT INTO avatars (url, user_id) VALUES (:url, :user_id)",
            __METHOD__,
            [
                'url' => $this->_image,
                'user_id' => $this->_id,
            ]
        );

        if (!$ret) {
            return false;
        }
        return true;
    }

    private function checkFile()
    {
        $fileType = ['gif' => 'image/gif', 'jpg' => 'image/jpeg', 'jpeg' => 'image/pjpeg', 'png' => 'image/png'];
        $numFileTypes = count($fileType);
        $i = 0;

        foreach ($fileType as $type => $image) {
            if ($_FILES['avatar']['type'] != $image) {
                $i++;
            } else {
                $typeFile = $type;
            }
        }
        if ($i == $numFileTypes) {
            return $typeFile = '';
        }

        $blacklist = array(".php", ".phtml", ".php3", ".php4");
        foreach ($blacklist as $item) {
            if (preg_match("/$item\$/i", $_FILES['avatar']['name'])) {
                $typeFile = '';
            }
        }

        return $typeFile;
    }


    public function getAllAvatars()
    {
        $db = \Core\Context::i()->getDb();
        $data = $db->fetchAll("SELECT url FROM avatars WHERE user_id = :user_id order by id desc",
            __METHOD__, ['user_id' => $_SESSION['id']]);
        if ($data) {
            return $data;
        }
        return false;
    }




}