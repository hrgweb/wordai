<?php

namespace App\Repositories;

/**
 * Spintax - A helper class to process Spintax strings.
 * @name Spintax
 * @author Jason Davis - https://www.codedevelopr.com/
 * Tutorial: https://www.codedevelopr.com/articles/php-spintax-class/
 */
class Spintax
{
    public function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'replace'),
            $text
        );
    }
    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
}
/* EXAMPLE USAGE */
// $spintax = new Spintax();
// $string = '{Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {Smith|Williams|Davis}!';
// echo $spintax->process($string);
/* NESTED SPINNING EXAMPLE */
// echo $spintax->process('{Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}');