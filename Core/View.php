<?php
namespace Core;
class View
{
    protected $_data;

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
        $this->$name = $value;
    }

    public function __get($name)
    {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        }
        return '';
    }

    public function render(string $tpl)
    {
        ob_start();
        include $tpl;
        return ob_get_clean();
    }
}