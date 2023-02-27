<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('pageSize', 10);

        $rolesData = Role::with(['permissions' => function ($query) {
            $query->where('level', '=', 3);
        }])->paginate($limit);

        return new RoleCollection($rolesData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $addData = ['name' => $request->name, 'guard_name' => 'api'];

        $hasRole = Role::where($addData)->first();

        if ($hasRole) {
            return $this->retData([], '名称重复', 202, 'error');
        }

        $role = Role::create($addData);

        if ($role->id) {
            return $this->retData($role->id, '添加成功');
        } else {
            return $this->retData([], '添加失败', 202, 'error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $name = $request->input('name');

        $hasRole = Role::where('name', $name)->first();

        if ($hasRole) {
            return $this->retData([], '名称重复', 202, 'error');
        }

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        return $this->retData([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->retData([], '删除成功');
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'role_ids' => 'required',
        ]);

        $rolses = $request->input('role_ids');

        if (!is_array($request->input('role_ids'))) {
            $rolses = explode(',', $request->input('role_ids'));
        }

        $user = User::find($request->input('user_id'));
        $roles = Role::wherein('id', $rolses)->get();

        $res = $user->syncRoles($roles);

        if ($res->id) {
            return $this->retData($res->id);
        } else {
            return $this->retData([], '操作失败', 202);
        }
    }
}
