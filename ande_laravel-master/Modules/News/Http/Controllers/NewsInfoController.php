<?php

namespace Modules\News\Http\Controllers;

use App\Http\Resources\PublicCollection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\News\Entities\NewsInfo as ThisModel;

class NewsInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $limit = $request->input('pageSize', 10);

        $field = ['id', 'category_id', 'title', 'ftitle', 'smalltext', 'writer', 'befrom', 'diggtop', 'user_id', 'status_f', 'created_at'];
        $data = ThisModel::filter($request->all())
            ->with('category:id,name')
            ->select($field)
            ->orderBy('id', 'desc')
            ->paginate($limit);

        return new PublicCollection($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('news::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $request->user_id = $request->user()->id;

        $model = ThisModel::create($request->all());
        if ($model->id) {
            return $this->retData($model->id, '添加成功');
        } else {
            return $this->retData([], '添加失败', 202, 'error');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = ThisModel::find($id);
        return $this->retData($data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('news::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
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

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $collection = collect(explode(',', $id));
        $res = ThisModel::destroy($collection->filter()->all());
        if ($res) {
            return $this->retData($res);
        } else {
            return $this->retData($res, '操作失败', 202);
        }
    }

    public function state($id, Request $request)
    {
        $model = new ThisModel();
        $res = $model->updateData($id, ['status_f' => $request->input('status_f',0)], $model);
        if ($res) {
            return $this->retData($res);
        } else {
            return $this->retData([], '操作失败', 202);
        }
    }
}
