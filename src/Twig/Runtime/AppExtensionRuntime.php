<?php

namespace App\Twig\Runtime;

use geoPHP\geoPHP;
use Twig\Extension\RuntimeExtensionInterface;

readonly class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private geoPHP $geoPHP,
    ) {
    }

    public function transliterate($value): false|string
    {
        $this->geoPHP::load('POLYGON((1 1,5 1,5 5,1 5,1 1),(2 2,2 3,3 3,3 2,2 2))','wkt');
        return iconv('UTF-8', 'ASCII//TRANSLIT', $value);
    }
}
