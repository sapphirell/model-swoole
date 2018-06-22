        
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

# db类使用方式
     $server->model->table('test')
     ->select()
     ->where(['id'=>'1'])
     ->all(function ($res){
           //这里接受\swoole_mysql异步回调,可以在这里放一些 $serv->push()之类的方法
           // var_dump($res);
     });

# 网站投递任务&使用swoole守护进程执行队列
    此处假定:用户在网站后台点击"限量抢拍商品a"。此时php后端响应用户的操作,并为数据类型为list,名为list的redis数据加值。
    例如:
        $task = json_encode(['class'=>'shopping','action'=>'goods','data'=>['time'=>12345678,'goods_id'=>23456,'user_id'=>233]]);
        $redis->rpush('list',$task);
    此时model-swoole就会每隔2秒处理一次,将class对应App/controller/Task/shopping.php 将action对应其中的goods($server,$user_message){...} 方法
       同时整串json都会投递到这个$user_message函数的参数中
       
