<?php

namespace App\Http\Controllers\AdminPro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkermanController extends Controller
{
    public function index(Request $request, $id)
    {
        $title = "workerman.{$id}";
        return view('workerman.index', ['name' => $title, 'id' => $id]);
    }

    public function seedMsg()
    {
        // http://test.laravel9.com/workermans/seed_msg
        $client = stream_socket_client('tcp://127.0.0.1:5678', $errno, $errmsg, 1);
        // 推送的数据，包含用户，表示是给这个用户推送
        $data = array('message' => '发送成功啦', 'to_uid' => 1);

        // 发送数据，注意5678端口是Text协议的端口，Text协议需要在数据末尾加上换行符
        fwrite($client, json_encode($data) . "\n");
    }
}
