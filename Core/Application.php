<?php

namespace Core;

class Application
{

    /** @var Context */
    private $_context;

    protected function _init()
    {
        $this->_context = Context::i();

        $request = new Request();
        $dispatcher = new Dispatcher();
        //$db = new DB();

        $this->_context->setRequest($request);
        $this->_context->setDispatcher($dispatcher);
        //$this->_context->setDb($db);
    }

    public function run()
    {

        try {
            $this->_init();
            $this->_context->getDispatcher()->dispatch();
            $dispatcher = $this->_context->getDispatcher();


            $controllerFileName = 'App\Controller\\' . $dispatcher->getControllerName();
            if (!class_exists($controllerFileName)) {
                throw new Exception\Error404('Class ' . $controllerFileName . ' not exists');
            }
            /** @var Controller */
            $controllerObj = new $controllerFileName();


            $actionFuncName = $dispatcher->getActionName();
            if (!method_exists($controllerObj, $actionFuncName)) {
                throw new Exception\Error404('Method ' . $actionFuncName . ' not found in ' . $controllerFileName);
            }

            $tpl = '../App/Templates/' . $dispatcher->getControllerName() .
                '/' . $dispatcher->getActionToken() . '.phtml';

            $view = new View();
            $controllerObj->view = $view;

            $controllerObj->$actionFuncName();
            if ($controllerObj->needRender()){
                $html = $view->render($tpl);
                echo $html;
            }



        } catch (Exception\Error404 $e) {
            header('HTTP/1.0 404 Not Found');
            trigger_error($e->getMessage());

        }


    }
}
