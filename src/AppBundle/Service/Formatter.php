<?php
namespace AppBundle\Service;


/**
 * Class Formatter
 *
 * @package AppBundle\Service
 */
class Formatter {

    public function price($number, $decimals = 0, $decPoint = '.', $thousandsSep = ' ')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price . ' Kč';

        return $price;
    }

}