<?php

namespace AppBundle\Services;

interface CardNumberGenerator
{
    /**
     * @param int $length
     * @return string
     */
    public function generate($length);

}