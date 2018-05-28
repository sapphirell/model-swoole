<?php
namespace App\Model;

class BaseModel
{
    /**
     * @var \swoole_mysql
     */
    public $mysql;
    /**
     * @var swoole db
     */
    public static $db;

    public static $orm;
    /**
     * @var self db class
     */
    public $queryParam;
    /**
     * @var array 提供的链式操作方法
     */
    protected $method;

    public function __construct()
    {
        $this->method = ['where','select','limit','order','table'];
        $this->mysql = new \swoole_mysql();
        $this->connect();

    }
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (in_array($name, $this->method))
        {
            $this->queryParam[$name] = reset($arguments);
            return $this;
        }
    }
    public function clear()
    {
        $this->querySql = [] ;
        return $this ;
    }
    public function connect()
    {
        $this->mysql->connect(
            [
                'host' => '127.0.0.1',
                'user' => 'root',
                'password' => '123',
                'database' => 'fantuanpu2018',
                'charset' => 'utf8'
            ],
            function ($db, $result) {
                if ($result)
                    return self::$db = $db;
                else
                    echo "Mysql connect failed \n";

            });

    }
    public function all($callback)
    {
        if($callback instanceof \Closure )
            return self::$db->query($this->getSql(),function ($db,$res) use ($callback)
            {
                $callback($res);
            });
        else
            return false;

    }
    public function getSql()
    {
        //select
        $select = $this->queryParam['select']
            ? rtrim(implode(",",$this->queryParam['select']),',')
            : " * ";

        //表
        if ($this->queryParam['table'])
        {
            $table = $this->queryParam['table'];
        }

        $sql = "SELECT {$select} FROM {$table} WHERE 1=1 ";
        //where
        foreach ($this->queryParam['where'] as $key=>$value)
        {
            $sql .= " AND `{$key}` = '$value'";
        }
        return $sql;
    }

}