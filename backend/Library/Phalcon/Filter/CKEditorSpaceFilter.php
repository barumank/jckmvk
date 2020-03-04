<?php

namespace Backend\Library\Phalcon\Filter;

/**
 * Class CKEditorSpaceFilter
 *
 * @author  Artem Pavlovskiy <tema23p@gmail.com>
 *
 * @package Backend\Library\Phalcon\Filter
 */
class CKEditorSpaceFilter
{
	public function filter($value)
	{
		return str_replace('&nbsp;', ' ', $value);
	}

}