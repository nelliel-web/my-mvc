<?php

namespace App\Models\Index;

class indexModel
{
    public $_name = 'Alexandr';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

}