<?php
namespace App\Controller;

use App\Server\Cache;
use App\Server\CacheKey;

class IM
{
    public $cache;
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function saveConnect($server,$frame,$user_json)
    {
        //        $this->cache->set('oop','ok');
        //        $frame->
    }

    /**
     * 身份验证
     * @param $server
     * @param $frame
     * @param $userMessage
     */
    public function identify($server,$frame,$userMessage)
    {
        $fd_cacheKey = CacheKey::FD_USER . $frame->fd;
        $uid_cacheKey = CacheKey::USER_FD . $userMessage['user_id'];
        //存储uid映射$fd一对多关系
        $this->cache->sadd($uid_cacheKey,$frame->fd);
        //存储$fd映射的用户信息
        $this->cache->hmset($fd_cacheKey,[
            'user_id'   => $userMessage['user_id'],
            'user_name' => $userMessage['user_name'],
        ]);
        //存储
        //        $data = $this->cache->HMget($cacheKey,['user_name']);
        //        var_dump($data);
        return true;
    }
}