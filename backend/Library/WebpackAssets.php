<?php

namespace Backend\Library;

use Phalcon\Assets\Manager;

/**
 * Class WebpackAssets
 *
 * @author  Gubarev Sergey <sj.gubarev@gmail.com>
 *
 * @package App
 */
class WebpackAssets extends Manager
{
    private $_assets_paths = [];
    private $hash = '';

    /**
     * Phalcon\Assets\Manager
     *
     * @param array $options
     */
    public function __construct($options)
    {
        parent::__construct($options);

        if (file_exists($options['path'])) {
            $this->_assets_paths = json_decode(file_get_contents($options['path']), true);
        }
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return WebpackAssets
     */
    public function setHash(string $hash): WebpackAssets
    {
        $this->hash = $hash;
        return $this;
    }


    /**
     * Adds a javascript resource to the 'js' collection
     * <code>
     * $assets->addJs('scripts/jquery.js');
     * $assets->addJs('http://jquery.my-cdn.com/jquery.js', false);
     * </code>
     *
     * @param string $path
     * @param mixed $local
     * @param mixed $filter
     * @param mixed $attributes
     *
     * @return Manager
     */
    public function addJs($path, $local = true, $filter = true, $attributes = null)
    {

        if (isset($this->_assets_paths[$path]['js'])) {
            $path = $this->_assets_paths[$path]['js'];
        }

        if ($local && !empty($this->hash)) {
            $path .= '?r=' . $this->hash;
        }

        parent::addJs($path, $local, $filter, $attributes);

        return $this;
    }

    /**
     * Adds a Css resource to the 'css' collection
     * <code>
     * $assets->addCss('css/bootstrap.css');
     * $assets->addCss('http://bootstrap.my-cdn.com/style.css', false);
     * </code>
     *
     * @param string $path
     * @param mixed $local
     * @param mixed $filter
     * @param mixed $attributes
     *
     * @return Manager
     */
    public function addCss($path, $local = true, $filter = true, $attributes = null)
    {
        if (isset($this->_assets_paths[$path]['css'])) {
            $path = $this->_assets_paths[$path]['css'];
        }

        if ($local && !empty($this->hash)) {
            $path .= '?r=' . $this->hash;
        }

        parent::addCss($path, $local, $filter, $attributes);

        return $this;
    }

}