<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $name = 'lzc';

    public function __construct()
    {

    }

    public function retData($result=[],$message='操作成功',$code=200,$type='')
    {
        empty($type) && $type = $code==200 ? 'success' : 'error';
        $response = [
            'code' => $code,
            'message' => $message,
            'result' => $result,
            'type' => $type
        ];
        return response($response, 200);
    }
}
