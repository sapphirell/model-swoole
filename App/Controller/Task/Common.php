<?php
namespace App\Controller\Task;

use App\Controller\Base;
use App\Server\CacheKey;

class Common extends Base
{
    public function task()
    {
        echo "hello";
    }
    public function user_task($server,$user_message)
    {
        return $this->shell_notice($user_message);
    }
}