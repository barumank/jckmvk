<?php

namespace Backend\Library\SEO;

use Phalcon\Mvc\User\Component;
use Phalcon\Tag;

/**
 * Class SEO
 *
 * @author  Gubarev Sergey <sj.gubarev@gmail.com>
 *
 * @package Backend\Library
 */
class SEO extends Component
{
	protected $_default = [
		'title'       => '',
		'description' => '',
		'keywords'    => [],
	];

	protected $_meta = [
		'title'       => '',
		'description' => '',
		'keywords'    => [],
	];

	protected $_openGraph = [
		'title'       => '',
		'description' => '',
		'image'       => '',
	];

	/**
	 * SEO constructor.
	 */
	public function __construct()
	{
		$this->tag->setDocType(Tag::HTML5);
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setDefaultTitle($value)
	{
		$this->_default['title'] = $value;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setDefaultDescription($value)
	{
		$this->_default['description'] = $value;

		return $this;
	}

	/**
	 * @param $value
	 *
	 * @return $this
	 */
	public function setDefaultKeywords($value)
	{
		if ( is_scalar($value) ) {
			$this->_default['keywords'] = array_filter(explode(',', $value));

			return $this;
		}

		if ( is_array($value) ) {
			$this->_default['keywords'] = array_filter($value);

			return $this;
		}

		return $this;
	}

	/**
	 * @param $value
	 *
	 * @return $this
	 */
	public function setKeywords($value)
	{
		if ( is_scalar($value) ) {
			$this->_meta['keywords'] = array_filter(explode(',', $value));

			return $this;
		}

		if ( is_array($value) ) {
			$this->_meta['keywords'] = array_filter($value);

			return $this;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getKeywords()
	{
		return implode(', ', $this->_meta['keywords']);
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setDescription($value)
	{
		$this->_meta['description'] = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->_meta['description'];
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setTitle($value)
	{
		$this->_meta['title'] = $value;

		return $this;
	}

	/**
	 * Добавить текст к началу заголовка
	 *
	 * @param string $value
	 * @param string $delimiter
	 *
	 * @return $this
	 */
	public function prependTitle($value, $delimiter = '')
	{
		if ( empty($this->_meta['title']) ) {
			$this->_meta['title'] = $value;
		}

		$this->_meta['title'] = $value . $delimiter . $this->_meta['title'];

		return $this;
	}

	/**
	 * Добавить текст к концу заголовка
	 *
	 * @param string $value
	 * @param string $delimiter
	 *
	 * @return $this
	 */
	public function appendTitle($value, $delimiter = '')
	{
		if ( empty($this->_meta['title']) ) {
			$this->_meta['title'] = $value;
		}

		$this->_meta['title'] = $this->_meta['title'] . $delimiter . $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->_meta['title'];
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setOGTitle($value)
	{
		$this->_openGraph['title'] = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOGTitle()
	{
		return !empty($this->_openGraph['title']) ? $this->_openGraph['title'] : $this->_meta['title'];
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setOGDescription($value)
	{
		$this->_openGraph['description'] = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOGDescription()
	{
		return !empty($this->_openGraph['description']) ? $this->_openGraph['description'] : $this->_meta['description'];
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setOGImage($value)
	{
		$this->_openGraph['image'] = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOGImage()
	{
		return $this->_openGraph['image'];
	}

	/**
	 * @author Gubarev Sergey <sj.gubarev@gmail.com>
	 *
	 * @return string
	 */
	public function output()
	{
		$this->_prepareToOut();

		$output = [];

		foreach ( $this->_meta as $name => $content ) {

			if ( empty($content) ) {
				continue;
			}

			if ( $name === 'title' ) {
				$output[] = Tag::tagHtml('title', null, false, true) . $content . Tag::tagHtmlClose('title');

				continue;
			}

			if ( $name === 'keywords' ) {
				$content = implode(', ', $content);
			}

			$output[] = Tag::tagHtml('meta', ['name' => $name, 'content' => $content], true, true);
		}

		foreach ( $this->_openGraph as $name => $content ) {
			if ( empty($content) ) {
				continue;
			}

			$output[] = Tag::tagHtml('meta', ['name' => "og:{$name}", 'content' => $content], true, true);
		}

		return implode(PHP_EOL, $output);
	}

	private function _prepareToOut()
	{
		foreach ( $this->_meta as $k => $v ) {
			if ( empty($v) && array_key_exists($k, $this->_default) ) {
				$this->_meta[$k] = $this->_default[$k];
			}
		}

		foreach ( $this->_openGraph as $k => $v ) {
			if ( empty($v) && array_key_exists($k, $this->_meta) ) {
				$this->_openGraph[$k] = $this->_meta[$k];
			}
		}
	}
}