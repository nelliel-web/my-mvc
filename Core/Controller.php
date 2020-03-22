<?php

namespace Core;

class Controller
{
    /** @var View */
    public $view;

    /** @var bool */
    protected $_render = true;

    /**
     * @return bool
     */
    public function needRender(): bool
    {
        return $this->_render;
    }

    public function __construct()
    {
        if (!isset($_SESSION['id'])) {
            $_SESSION['name'] = $_SERVER['HTTP_COOKIE'];
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        }

    }

    public function isSession()
    {
        if (isset($_SESSION['id'])) {

            $db = \Core\Context::i()->getDb();
            $data = $db->fetchOne("SELECT id FROM users WHERE id = :id and email = :email", __METHOD__,
                ['id' => $_SESSION['id'], 'email' => $_SESSION['email']]);
            if (!empty($data)) {
                return true;
            }
            return false;
        }
        return false;
    }

}