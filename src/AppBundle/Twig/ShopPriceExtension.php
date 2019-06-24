<?php

namespace AppBundle\Twig;

use AppBundle\Twig\AppRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use AppBundle\Service\Formatter;

class ShopPriceExtension extends AbstractExtension
{
    private $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('price', array($this, 'priceFilter')),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ' ')
    {
        return $this->formatter->price($number, $decimals = 0, $decPoint = '.', $thousandsSep = ' ');

        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price . ' Kč';

        return $price;
    }
}
