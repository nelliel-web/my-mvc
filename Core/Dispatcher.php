<?php

namespace Core;

class Dispatcher
{
    const DEFAULT_CONTROLLER = 'Index';
    const DEFAULT_ACTION = 'index';

    private $_controllerName = '';
    private $_actionToken = '';

    protected function getRoutes()
    {
        return [
            'Login' => [
                'index' => 'User.login',
            ],
            'Register' => [
                'index' => 'User.register',
            ],
        ];
    }

    public function dispatch()
    {
        $request = Context::i()->getRequest();

        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();

        if (!$controllerName or !$this->check($controllerName)) {
            $this->_controllerName = self::DEFAULT_CONTROLLER;
        } else {
            $this->_controllerName = ucfirst(strtolower($controllerName));
        }

        if (!$actionName or !$this->check($actionName)) {
            $this->_actionToken = self::DEFAULT_ACTION;
        } else {
            $this->_actionToken = strtolower($actionName);
        }

        $routes = $this->getRoutes();
        if (
            isset($routes[$this->_controllerName]) and
            isset($routes[$this->_controllerName][$this->_actionToken])
        ) {
            list($this->_controllerName, $this->_actionToken) = explode('.',
                $routes[$this->_controllerName][$this->_actionToken]);
        }
    }

    private function check(string $key)
    {
        return preg_match('/[a-zA-Z0-9]+/', $key);
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->_controllerName;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->_actionToken . 'Action';
    }

    public function getActionToken()
    {
        return $this->_actionToken;
    }
}
