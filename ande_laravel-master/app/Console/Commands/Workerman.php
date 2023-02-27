<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Workerman\Worker;
use function PHPUnit\Framework\isJson;

class Workerman extends Command
{
    protected $signature = 'Workerman {action} {--daemonize}';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(\Request $request)
    {
        global $argv;//定义全局变量
        $arg = $this->argument('action');
        $argv[1] = $arg;
        $argv[2] = $this->option('daemonize') ? '-d' : '';//该参数是以daemon（守护进程）方式启动

        global $newWorker;
        // 创建一个Worker监听2345端口，使用websocket协议通讯
        $newWorker = new Worker("websocket://0.0.0.0:2345");
        $newWorker->uidConnections = array();//在线用户连接对象
        $newWorker->uidInfo = array();//在线用户的用户信息

        $newWorker->name = 'chat';
        // 启动4个进程对外提供服务
        $newWorker->count = 4;

        //当启动workerman的时候 触发此方法
        $newWorker->onWorkerStart = function ($connection) {
            //监听一个内部端口，用来接收服务器的消息，转发给浏览器
            $inner_newWorker = new Worker('Text://127.0.0.1:5678');
            $inner_newWorker->onMessage = function ($connection_admin, $data) {
                global $newWorker;
                // $data数组格式，里面有uid，表示向那个uid的页面推送数据
                if (isJson($data)) {
                    $data = json_decode($data, true);
                    $finalRes = false;
                    if (isset($newWorker->uidConnections["{$data['to_uid']}"])) {
                        $connection = $newWorker->uidConnections[$data['to_uid']];
                        $connection->send(json_encode($data));
                    }
                }
            };
            $inner_newWorker->listen();
        };

        //当浏览器连接的时候触发此函数
        $newWorker->onConnect = function ($connection,$data) {
            global $request;
            var_dump($request->all());
        };

        //$connection 当前连接的人的信息 $data 发送的数据
        $newWorker->onMessage = function ($connection, $data) {
            $connection->send($data);
            if (isJson($data)) {
                $data = json_decode($data, true);
                $data = $data['data'];
                $connection->send(json_encode($data));
                switch ($data['type']) {
                    case 'login':
                        $this->login($connection, $data);
                        break;
                    case 'send_message':
                        $this->sendMessage($connection, $data);
                        break;
                    case 'send_message_all':
                        $this->sendMessageAll($connection, $data);
                        break;
                }
            }
        };

        //浏览器断开链接的时候触发
        $newWorker->onClose = function ($connection) {
            global $newWorker;
            unset($newWorker->uidConnections["$connection->uid"]);
        };

        Worker::runAll();
    }

    public function sendMessage($connection, $data)
    {
        global $newWorker;
        if (isset($data['to_uid'])) {
            if (isset($newWorker->uidConnections["{$data['to_uid']}"])) {
                $to_connection = $newWorker->uidConnections["{$data['to_uid']}"];
                $to_connection->send($data['message']);
            }
        }
    }

    public function sendMessageAll($connection, $data)
    {
        global $newWorker;
        foreach ($newWorker->uidConnections as $to_connection) {
            $to_connection->send($data['message']);
        }
    }

    public function login($connection, $data, \Request $request)
    {
        global $newWorker;
        $user = $request->user();
        //$connection->uid = $data['uid'];
        //$newWorker->uidConnections["{$data['uid']}"] = $connection;
        $connection->send($user);
    }

}
