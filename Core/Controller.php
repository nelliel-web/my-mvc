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

}