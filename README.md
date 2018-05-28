        
 #  测试用例
    var exampleSocket = new WebSocket("ws://127.0.0.1:8001");
    var identify = {
        "type"      : "IM",
        "user_id"   : $('#uid').val(),
        "user_name" : $('#username').val(),
        "action"    : "identify"
    };

    exampleSocket.onopen = function (event) {
        exampleSocket.send(JSON.stringify(identify));
    }
    
# Route.php路由

    会根据json中的Type映射到Class中的Controller,会根据action映射到该class内的function
    
# Container.php
    提供依赖注入实现,例子
    $App = new Container('\App\Controller\\'.$userMessage['type']);
    $App->builder($userMessage['action'],$server,$frame,$userMessage);
    DB类用法
    $res =  $server->model
                    ->table('test')
                    ->select()
                    ->where(['id'=>'1'])
                    ->all(function ($res){
                        //这里接受\swoole_mysql异步回调,可以在这里放一些 $serv->push()之类的方法
                        //                                var_dump($res);
                    });
