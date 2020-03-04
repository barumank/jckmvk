<?php
/**
 * Created by PhpStorm.
 * User: danik
 * Date: 04.08.17
 * Time: 17:05
 */

namespace Backend\Library\SEO;


class Meta
{

    protected $keywords;

    protected $description;

    public function setKeywords($keywords) {
        $this->keywords = $keywords;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getKeywords() {
        return $this->keywords;
    }

    public function getDescription() {
        return $this->description;
    }

    public function __construct($keywords = null, $description = null) {
        $this->keywords = $keywords;
        $this->description = $description;
    }
}