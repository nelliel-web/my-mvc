<?php

namespace App\Models\Index;

class IndexModel
{
    private $_id;
    private $_name;
    private $_email;
    private $_passwordHash;
    private $_desc;
    private $_age;
    private $_image;

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->_image;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * @param mixed $desc
     */
    public function setDesc($desc): void
    {
        $this->_desc = $desc;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->_age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age): void
    {
        $this->_age = $age;
    }


    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->_email = $email;
    }

    /**
     * @return mixed
     */
    public function getPasswordHash()
    {
        return $this->_passwordHash;
    }

    /**
     * @param mixed $passwordHash
     */
    public function setPasswordHash($passwordHash): void
    {
        $this->_passwordHash = $passwordHash;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->_name = $name;
    }

    public static function genPasswordHash(string $password)
    {
        return sha1('d0=+0' . $password . 'k5\R0.-~lL');
    }

    public function getFromPost(array $post, $typeForm = 'reg')
    {
        if ($typeForm == 'reg') {
            $post['name'] = $_POST['name'] ?? '';
            $post['email'] = $_POST['email'] ?? '';
            $post['pass'] = $_POST['pass'] ?? '';
            $post['age'] = $_POST['age'] ?? '';
            $post['desc'] = $_POST['desc'] ?? '';
            $post['image'] = $_FILES['avatar'] ?? '';
            return $post;
        }elseif ($typeForm == 'auth'){
            $post['email'] = $_POST['email'] ?? '';
            $post['pass'] = $_POST['pass'] ?? '';
            return $post;
        }
        return '';
    }

    public function save()
    {

        if ($this->hasEmail($this->_email)) {
            $_SESSION['error'] = 'Пользователь с таки Email Уже зарегистрирован';
            return false;
        }

        $db = \Core\Context::i()->getDb();
        $ret = $db->exec(
            "INSERT INTO users (`name`, email, pass, age, `desc`) VALUES (:name, :email, :pass, :age, :desc)",
            __METHOD__,
            [
                'name' => $this->_name,
                'email' => $this->_email,
                'pass' => $this->_passwordHash,
                'age' => $this->_age,
                'desc' => $this->_desc,
            ]
        );

        if (!$ret) {
            return false;
        }
        $id = $db->lastInsertId();
        $this->_id = $id;
        $this->uploadAvatar();
        $this->setSess();
        $this->_register = true;
        return true;
    }

    private function hasEmail($email)
    {
        $db = \Core\Context::i()->getDb();
        $data = $db->fetchOne("SELECT email FROM users WHERE email = :email", __METHOD__,
            ['email' => $email]);
        if ($data) {
            return true;
        }
        return false;
    }

    public function get(int $id)
    {
        $db = \Core\Context::i()->getDb();
        $data = $db->fetchOne("SELECT * FROM users WHERE id = :id", __METHOD__, ['id' => $id]);
        if ($data) {
            $this->loadData($data);
            return true;
        }
        return false;
    }

    public function loadData(array $data, $new = false)
    {
        if (isset($data['id'])) {
            $this->_id = $data['id'];
        }
        $this->_name = $data['name'];
        if ($new) {
            $this->_passwordHash = self::genPasswordHash($data['pass']);
        } else {
            $this->_passwordHash = $data['pass'];
        }
        $this->_image = $data['image'] ?? '';
        $this->_email = $data['email'];
        $this->_age = $data['age'];
        $this->_desc = $data['desc'];
    }


    public function check(&$error = '')
    {
        if (!$this->_name) {
            $error = 'Пустое поле Имя';
            return false;
        }
        if (!$this->_passwordHash) {
            $error = 'Не введен пароль';
            return false;
        }
        if (!$this->_email) {
            $error = 'Пустое поле email';
            return false;
        }
        if (!$this->_age) {
            $error = 'Пустое поле Возраст';
            return false;
        }
        if (!$this->_desc) {
            $error = 'Пустое поле Описание';
            return false;
        }

        return true;
    }

    public function authUser(array $data)
    {
        $this->_passwordHash = self::genPasswordHash($data['pass']);
        $this->_email = $data['email'];
        $db = \Core\Context::i()->getDb();
        $userId = $db->fetchOne("SELECT id FROM users WHERE email = :email and pass = :pass",
            __METHOD__,
            ['email' => $data['email'], 'pass' => $this->_passwordHash]);

        if (!$userId) {
            return false;
        }
        $this->_id = $userId['id'];
        unset($_SESSION['error_auth']);
        $this->setSess();
        return true;

    }

    private function setSess()
    {
        if (!isset($_SESSION['id'])) {
            $_SESSION['id'] = $this->_id;
        }
        if (!isset($_SESSION['email'])) {
            $_SESSION['email'] = $this->_email;
        }
    }


    private function uploadAvatar()
    {
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

}
