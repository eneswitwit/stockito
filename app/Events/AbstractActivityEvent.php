<?php

namespace App\Events;

abstract class AbstractActivityEvent implements ActivityEventInterface
{
    /**
     * @var integer
     */
    public $type;
}