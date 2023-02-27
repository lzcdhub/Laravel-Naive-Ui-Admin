<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberCollection;
use App\Models\Member as ThisModel;
use Illuminate\Http\Request;
use Modules\EasyWechat\Entities\WxMember;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('pageSize', 10);
        $data = ThisModel::filter($request->all())->orderBy('id','desc')->paginate($limit);
        return new MemberCollection($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
        ]);

        $model = new ThisModel();
        $res = $model->createData($request->all(),$model);
        if ($res->id) {
            return $this->retData($res->id);
        } else {
            return $this->retData([], '添加失败', 202);
        }
    }

    public function show($id)
    {
        $data = ThisModel::find($id);
        return $this->retData($data);
    }

    public function update(Request $request, $id)
    {
        $model = new ThisModel();
        $res = $model->updateData($id, $request->all(), $model);
        if ($res) {
            return $this->retData($res);
        } else {
            return $this->retData([], '修改失败', 202);
        }
    }

    public function destroy($id)
    {
        $deleted = ThisModel::destroy($id);
        WxMember::where('member_id', $id)->delete();

        if ($deleted) {
            return $this->retData([$id], '删除成功');
        } else {
            return $this->retData([], '删除失败', '202');
        }
    }
}
