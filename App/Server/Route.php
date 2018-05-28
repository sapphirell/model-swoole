<?php
/***
 *  路由类
 */
namespace App\Server;

use App\Controller\IM;
use App\Model\BaseModel;
use App\Model\TestModel;

class Route
{
    public $websocketServer;
    public $model;
    public function __construct()
    {

        /**
         * 初始化websocket
         */
        $this->websocketServer = new \swoole_websocket_server("0.0.0.0", "8001");


    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
    protected function call_shell($msg)
    {
        echo $msg . '\n';
        return true;
    }
    public function start_ws()
    {
        $this->websocketServer->on("start",     [$this , "ws_onStart"]);
        $this->websocketServer->on("workerStart",     [$this , "ws_onWorkerStart"]);
        $this->websocketServer->on("open",      [$this , "ws_open"]);
        $this->websocketServer->on("message",   [$this , "ws_onMessage"]);
        $this->websocketServer->on("close",     [$this , "ws_onClose"]);
        $this->websocketServer->on('request', function ($request, $response) {
            // 接收http请求从get获取message参数的值，给用户推送
            // $this->server->connections 遍历所有websocket连接用户的fd，给所有用户推送
        });
        $this->websocketServer->start();
    }
    public function ws_open(\swoole_websocket_server $server, $request)
    {
        if (empty($server->model))
        {
            $server->model = new BaseModel();
        }
        else
        {
            $res =  $server->model
                ->table('test')
                ->select()
                ->where(['id'=>'1'])
                ->all(function ($res){
                    //                                var_dump($res);
                });

            //            BaseModel::$db->query("SELECT * FROM pre_comic_area",function ($db,$res) {
            //                var_dump($db);
            //                var_dump($res);
            //                foreach ($res as $value)
            //                {
            //                    echo $value ."\n";
            //                }
            //                BaseModel::$db->query("INSERT INTO test SET val = '123'",function (){});
            //            });
        }

        echo "server: handshake success with fd{$request->fd}\n";
    }
    public function ws_onMessage(\swoole_websocket_server $server, $frame)
    {
        //        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        //        $server->push($frame->fd, "this is server");
        $userMessage = json_decode($frame->data,true);
        if (!$userMessage)
        {
            return false;
        }
        if (!$userMessage['type'] || !$userMessage['action'])
        {
            return $this->call_shell("Type or action not found! ");
        }
        //使用依赖注入容器做伪路由
        $App = new Container('\App\Controller\\'.$userMessage['type']);
        return $App->builder($userMessage['action'],$server,$frame,$userMessage);
    }
    public function ws_onClose(\swoole_websocket_server $server,$fd)
    {}
    public function ws_onStart(\swoole_websocket_server $server)
    {

    }
    public function ws_onWorkerStart(\swoole_websocket_server $server, $worker_id)
    {
        if (empty($server->model))
        {
            $server->model = new BaseModel();
        }

    }

}