<?php

namespace AppBundle\Services;

use AppBundle\Utils\LuhnAlgorithm;

class LuhnNumberGenerator implements CardNumberGenerator
{
    /**
     * @param int $length
     * @return string
     */
    public function generate($length)
    {
        $number = '';
        for ($i = 0; $i < $length - 2; $i++) {
            $number .= rand(0, 9);
        }
        $number .= LuhnAlgorithm::calculateCheckDigit($number);
        return $number;
    }

}