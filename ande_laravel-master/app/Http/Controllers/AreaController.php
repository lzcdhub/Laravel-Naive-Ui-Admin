<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use DB;
use Illuminate\Http\Request;
use App\Models\Area as ThisModel;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $pid = $request->input('pid', 0);
        $indexName = 'area_data_' . $pid;

        $data = Cache::get($indexName, function () use ($pid, $indexName) {
            $res = ThisModel::where('pid', '=', $pid)->get()->each(function ($item) {
                $item->childrenNum > 1 ? $item['isLeaf'] = false : $item['isLeaf'] = true;;
                return $item;
            });
            Cache::put($indexName, $res);
            return $res;
        });

        return $this->retData($data, 'area_data_' . $pid);
    }

    public function show($id)
    {
        $data = ThisModel::find($id);
        return $this->retData($data);
    }

}
