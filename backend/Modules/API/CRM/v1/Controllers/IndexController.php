<?php

namespace Backend\Modules\API\CRM\v1\Controllers;

use Backend\Modules\API\CRM\v1\Controller;

class IndexController extends Controller
{
	public function IndexAction()
	{
		$this->jsonResponse->sendSuccess(['fg'=>1]);
	}

    public function ErrorAction()
    {
         $this->jsonResponse->sendError(['error']);
	}
}