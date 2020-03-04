<?php

namespace Backend\Library\Parsers\Teplo3000\Parser;


use Backend\Library\Parsers\Teplo3000\Parser\TDO\Category;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Product;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Attribute;

class ProductParser
{
    public function parse(string $text): Product
    {
        /**@var Product $product */
        $product = (new Product())
            ->setName($this->getName($text))
            ->setUrlImage($this->getUrlImage($text))
            ->setCategories($this->getCategories($text))
            ->setPrice($this->getPrice($text))
            ->setProperty($this->getProperty($text));

        return $product;
    }

    public function getName(string $text): string
    {
        $pattern = '#<h1>(.*?)</h1>#usi';
        if (empty(preg_match($pattern, $text, $matches))) {
            return '';
        }
        return $matches[1];
    }

    /**
     * @param string $text
     * @return string[]
     */
    private function getCategories(string $text): array
    {
        $out = [];
        $pattern = '#<div\s+class="hk">(.*?)</div>#usi';
        if (empty(preg_match($pattern, $text, $matches))) {
            return $out;
        }
        $breadCrumbs = $matches[1];
        $pattern = '#<a\s+href="([^"]+)">(.*?)</a>#usi';
        if (empty(preg_match_all($pattern, $breadCrumbs, $matches))) {
            return $out;
        }
        $length = count($matches[1]);
        for ($i = 0; $i < $length; $i++) {
            $url = rtrim(trim($matches[1][$i]), '/');
            $out[] = new Category(trim($matches[2][$i]), $url);
        }
        return $out;
    }

    private function getPrice(string $text): ?float
    {
        $pattern = '#<div class="price">(.*?)(?:<i[^>]+></i>)?</div>#usi';
        if (empty(preg_match($pattern, $text, $matches))) {
            return null;
        }
        $price = trim($matches[1]);
        $price = str_replace(' ', '', $price);

        return (float)$price;
    }

    private function getProperty(string $text): array
    {
        $out = [];
        $pattern = '#<div class="roww">\s+<div class="cell[^>]+>(.*?)</div>\s+<div class="cell[^>]+>(.*?)</div>\s+</div>#usi';
        if (empty(preg_match_all($pattern, $text, $matches))) {
            return $out;
        }
        $length = count($matches[0]);
        for ($i = 0; $i < $length; $i++) {
            $key = str_replace(':', '', $matches[1][$i]);
            $key = trim(strip_tags($key));
            $value = trim(strip_tags($matches[2][$i]));
            $out[] = new Attribute($key, $value);
        }
        return $out;
    }

    private function getUrlImage(string $text): ?string
    {
        $pattern = '#<div\s+class="img_main">[^<]+<a\s+href="([^"]+)"[^>]+>#usi';
        if (empty(preg_match($pattern, $text, $matches))) {
            return null;
        }
        return trim($matches[1]);
    }

}
