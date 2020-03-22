<?php

namespace Core;

class DB
{
    /** @var PDO */
    private $_pdo;
    private $_log = [];

    private function getConnection()
    {
        if (!$this->_pdo) {
            $this->_pdo = new \PDO('mysql:host=localhost;dbname=my-mvc', 'root', '');
        }
        return $this->_pdo;
    }

    public function fetchAll(string $query, $_method, array $params = [])
    {
        $t = microtime(1);
        $pdo = $this->getConnection();
        $prepared = $pdo->prepare($query);

        $ret = $prepared->execute($params);

        if (!$ret) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }
        $data = $prepared->fetchAll(\PDO::FETCH_ASSOC);
        $affectedRows = $prepared->rowCount();
        $this->_log[] = [$query, microtime(1) - $t, $_method, $affectedRows];

        return $data;
    }

    public function fetchOne(string $query, $_method, array $params = [])
    {
        $t = microtime(1);
        $pdo = $this->getConnection();
        $prepared = $pdo->prepare($query);

        $ret = $prepared->execute($params);

        if (!$ret) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }
        $data = $prepared->fetchAll(\PDO::FETCH_ASSOC);
        $affectedRows = $prepared->rowCount();
        $this->_log[] = [$query, microtime(1) - $t, $_method, $affectedRows];
        if (!$data) {
            return false;
        }
        return reset($data);
    }

    public function exec(string $query, $_method, array $params = [])
    {
        $t = microtime(1);
        $pdo = $this->getConnection();
        $prepared = $pdo->prepare($query);

        $ret = $prepared->execute($params);

        if (!$ret) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return -1;
        }
        $affectedRows = $prepared->rowCount();

        $this->_log[] = [$query, microtime(1) - $t, $_method, $affectedRows];

        return true;
    }

    public function lastInsertId()
    {
        return $this->getConnection()->lastInsertId();
    }

    public function getLogHTML()
    {
        if (!$this->_log) {
            return '';
        }
        $res = '';
        foreach ($this->_log as $elem) {
            $res = $elem[1] . ': ' . $elem[0] . '(' . $elem[2] . ') [' . $elem[3] . ']' . "\n";
        }
        return '<pre>' . $res . '</pre>';
    }


}