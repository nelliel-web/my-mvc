<?php

namespace App\Models\User;

class UserModel
{

    private $_id;
    private $_name;
    private $_email;
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
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->_age;
    }

    public function UserAction()
    {
        if (!isset($_SESSION['id'])){
            return false;
        }
        $db = \Core\Context::i()->getDb();
        $userId = $db->fetchOne("SELECT id FROM users WHERE email = :email and id = :id",
            __METHOD__,
            ['email' => $_SESSION['email'], 'id' => $_SESSION['id']]);
        if (!$userId) {
            return false;
        }
        $this->_id = $userId['id'];

        $user = $db->fetchAll("SELECT * FROM users WHERE id = :id",
            __METHOD__,
            ['id' => $this->_id]);
        if (!$user) {
            return false;
        }
        $user = $user[0];

        $this->_name = $user['name'];
        $this->_email = $user['email'];
        $this->_age = $user['age'];
        $this->_desc = $user['desc'];

        $this->getAvatar($this->_id);

        return true;

    }

    public function getAvatar(int $id)
    {
        $db = \Core\Context::i()->getDb();
        $data = $db->fetchOne("SELECT url FROM avatars WHERE user_id = :user_id  ORDER BY id desc LIMIT 1",
            __METHOD__, ['user_id' => $id]);
        if ($data) {
            $this->_image = $data['url'];
            return true;
        }
        return false;
    }

    public function get(int $id)
    {
        $db = \Core\Context::i()->getDb();
        $data = $db->fetchOne("SELECT * FROM users WHERE id = :id", __METHOD__, ['id' => $id]);
        if ($data) {
            return true;
        }
        return false;
    }

    public function getAllUsers(string $order = 'ASC')
    {
        $db = \Core\Context::i()->getDb();
        $users = $db->fetchAll("SELECT email, `name`, age FROM `users` order by age $order", __METHOD__);
        if ($users) {
            $i=0;
            foreach ($users as $user){
                if ($user['age'] >= 18){
                    $users[$i]['ageText'] = 'Совершеннолетний';
                }else{
                    $users[$i]['ageText'] = 'НЕсовершеннолетний';
                }
                $i++;
            }
            return $users;
        }
        return false;
    }


    public function deliteAccount()
    {
        $db = \Core\Context::i()->getDb();
        $data = $db->exec("DELETE FROM `users` WHERE `id` = :id", __METHOD__, ['id' => $_SESSION['id']]);
        if ($data) {
            return true;
        }
        return false;
    }



}