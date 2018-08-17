<?php

namespace App\Models;

interface BaseUserInterface
{
    /**
     * @param $value
     * @return mixed
     */
    public function getNameAttribute ($value);

    /**
     * @return string
     */
    public function getUserTypeName (): string;
}