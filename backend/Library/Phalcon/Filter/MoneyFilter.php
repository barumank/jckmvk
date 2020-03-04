<?php

namespace Backend\Library\Phalcon\Filter;

/**
 * Class MoneyFilter
 *
 * @author  Pavlicskiy Artem <pavlivski@webempire.by>
 *
 * @package Backend\Library\Phalcon\Filter
 */
class MoneyFilter
{
	public function filter($value)
	{
		return trim(str_replace(',', '.', $value));
	}

}