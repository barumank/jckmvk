<?php


namespace Backend\Modules\Console\Tasks;


use Backend\Modules\Console\Task;

class TestTask extends Task
{
    public function LogAction()
    {
        $this->logger->error('test');
    }

}