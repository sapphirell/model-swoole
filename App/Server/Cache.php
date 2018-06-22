<?php
namespace App\Server;

class Cache
{
    public $redis;
    public function __construct()
    {
        $this->connect();
    }

    public function __call($name, $arguments)
    {
        $fnExists = function_exists(strtolower($name));
        if ($fnExists)
            //转换大小写后,本地方法存在则不走call
            return $this->{strtolower($name)}();

        $param = '$this->redis->'.$name.'(';

        for ($i = 0;$i < count($arguments) ; $i++)
            $param .= is_string($arguments[$i])
                ? "'" . $arguments[$i] . "'" . ','
                : "'" . json_encode($arguments[$i],true) . '\',' ;

        $param = rtrim($param,",");
        $param .= ');';
        //        echo $param;
        return eval($param);
    }

    public function connect()
    {
        try{
            $this->redis = new \redis();
            $this->redis->connect('127.0.0.1','6379');
        }
        catch (PDOException $exception)
        {
            var_dump($exception);
        }
    }
    public function HmGet($keys,array $param)
    {
        return $this->redis->hmget($keys, $param);
    }
    public function HmSet($keys,array $param)
    {
        return $this->redis->hmset($keys,$param);
    }
    public function sMembers($keys)
    {
        return $this->redis->smembers($keys);
    }
    public function rPop($keys)
    {
        return $this->redis->rpop($keys);
    }
    public function lPop($keys)
    {
        return $this->redis->lpop($keys);
    }
}