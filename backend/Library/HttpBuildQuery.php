<?php

namespace Backend\Library;

/**
 * Class HttpBuildQuery
 *
 *
 * @package Backend\Modules\Admin\Library\Pagination
 */
class HttpBuildQuery
{

    public function build($array)
    {
        if(empty($array)){
            return '';
        }

        return '?'.http_build_query($array);
    }
}