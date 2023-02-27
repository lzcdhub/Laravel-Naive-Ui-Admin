<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\News\Entities\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        //withDepth()->having('depth', '=', 1) 展现节点的时候，使用withDepth()方法，输出的数据会带一个深度字段depth
        $categoryData = Category::defaultOrder()->get()->toTree();
        return $this->retData(['list' => $categoryData], '');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
//        $node = Category::find(10);
//        $parentNode = Category::find(1);
//        $aa = $node->appendToNode($parentNode)->save();
        $bool = Category::isBroken();
        return $this->retData([$bool], '检查树节点是否被破环');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $model = new Category(['name' => $request->input('name')]);

        if ($request->input('parent_id')) {
            $parentNode = Category::find($request->input('parent_id'));
            $model->appendToNode($parentNode)->save();
        } else {
            $model->save();
        }

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
        return view('WxShop::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $operate = $request->input('operate', '');
        $length = $request->input('length', 1);
        $res = false;

        $node = Category::find($id);

        switch ($operate) {
            case 'up':
                $res = $node->up($length);
                break;
            case 'down':
                $res = $node->down($length);
                break;
            case 'root':
                $res = $node->saveAsRoot();//设置为根节点
                break;
            case 'neighbor':
                $neighbor = Category::find($request->input('neighbor_id'));
                $operatePro = $request->input('operate_pro', '');
                if ($operatePro == 'after') {
                    $res = $node->insertAfterNode($neighbor);
                } elseif ($operatePro == 'before') {
                    $res = $node->insertBeforeNode($neighbor);
                }
                break;
            case 'edit':
                $node->name = $request->input('name');
                if ($node->parent_id != $request->input('parent_id', null)) {
                    $node->parent_id = $request->input('parent_id', null);
                }
                $res = $node->save();
                break;
        }

        return $this->retData($res);
    }

    /**
     * Remove the specified resource from storage.
     * 使用模型的 delete 方法删除，节点的所有后代元素将一并删除
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $node = Category::find($id);
        $res = $node->delete();
        if ($res) {
            return $this->retData($res);
        } else {
            return $this->retData($res, '操作失败', 202);
        }
    }

    public function categorySelect(Request $request)
    {
        if($request->input('pid')){
            $categoryData = Category::defaultOrder()->descendantsOf($request->input('pid'))->toTree();
        }else{
            $categoryData = Category::defaultOrder()->get()->toTree();
        }
        return $this->retData(['list' => $categoryData], '');
    }
}
