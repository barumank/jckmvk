<?php


namespace Backend\Library\Phalcon\Form\Element;


class Recaptcha extends \Phalcon\Forms\Element
{
    const NAME = 'g-recaptcha-response';

    public function render($attributes = null)
    {
        $attributes = $this->getAttributes();
        $html = '<input type="hidden" name="'.$this->getName().'">';
        $params = [];
        foreach ($attributes as $key =>$value){
            $params[]="{$key}=\"{$value}\"";
        }
        $html .= '<div '.implode(' ',$params).'></div>';
        return $html;
    }
}