<?php
namespace App\Controller;

use App\Server\Cache;
use App\Server\CacheKey;

class Base
{
    public $cache;
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }
    public function shell_notice($msg)
    {
        echo $msg . "\r\n";
    }
}