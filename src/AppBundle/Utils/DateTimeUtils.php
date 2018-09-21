<?php

namespace AppBundle\Utils;

class DateTimeUtils
{
    public const FORMAT_MYSQL_TIMESTAMP = 'Y-m-d H:i:s';

    /**
     * @param \DateTime $datetime
     * @return string
     */
    public static function toSqlTimestamp($datetime)
    {
        return $datetime->format(self::FORMAT_MYSQL_TIMESTAMP);
    }

    /**
     * @param $string
     * @return bool|\DateTime
     */
    public static function fromSqlTimestamp($string)
    {
        if (strlen($string) === 10) {
            $string .= ' 00:00:00';
        }
        return \DateTime::createFromFormat(self::FORMAT_MYSQL_TIMESTAMP, $string);
    }

}