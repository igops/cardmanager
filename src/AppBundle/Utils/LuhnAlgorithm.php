<?php

namespace AppBundle\Utils;

/**
 * Luhn algorithm (a.k.a. modulus 10) is a simple formula used to validate variety of identification numbers.
 * It is not intended to be a cyrptographically secure hash function, it was designed to protect against accidental errors.
 * See http://en.wikipedia.org/wiki/Luhn_algorithm
 */
class LuhnAlgorithm
{
    private const SUM_TABLE = [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [0, 2, 4, 6, 8, 1, 3, 5, 7, 9]];

    /**
     * Calculate check digit according to Luhn's algorithm
     * New method (suggested by H.Johnson), see http://www.phpclasses.org/discuss/package/8471/thread/1/
     *
     * @param string $number
     * @return integer
     */
    public static function calculateCheckDigit($number)
    {
        $length = strlen($number);
        $sum = 0;
        $flip = 1;

        // Sum digits (last one is check digit, which is not in parameter)
        for ($i = $length - 1; $i >= 0; $i--) {
            $sum += self::SUM_TABLE[$flip++ & 0x1][$number[$i]];
        }
        // Multiply by 9
        $sum *= 9;

        // Last digit of sum is check digit
        return (int)substr($sum, -1, 1);
    }

}