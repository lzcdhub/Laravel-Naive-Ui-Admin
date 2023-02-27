<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    public function permissionsTree()
    {
        $permissions = Permission::all();
        $menusData = $this->subTree($permissions->sortBy('menu_sort')->toArray());
        return $this->retData(['list' => $menusData]);
    }

    public function addPermission(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'menu_title' => 'required',
        ]);

        $addData = ['name' => $request->name, 'guard_name' => 'api'];

        $hasPermission = Permission::where($addData)->first();

        if ($hasPermission) {
            return $this->retData([], '名称重复', 202, 'error');
        }

        $addData = array_merge($addData, [
            'pid' => $request->input('pid', 0),
            'level' => $request->input('level', 0),
            'menu_path' => $request->input('menu_path', ''),
            'menu_sort' => $request->input('menu_sort'),
            'menu_component' => $request->input('menu_component', ''),
            'menu_redirect' => $request->input('menu_redirect', ''),
            'menu_icon' => $request->input('menu_icon', ''),
            'menu_title' => $request->input('menu_title', ''),
        ]);

        $permission = Permission::create($addData);

        if ($permission->id) {
            return $this->retData($permission->id, '添加成功');
        } else {
            return $this->retData([], '添加失败', 202, 'error');
        }
    }

    public function editPermission(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'label' => 'required',
            'subtitle' => 'required'
        ]);

        $permission = Permission::where('id', $request->input('key'))->update([
            'menu_path' => $request->input('path', ''),
            'menu_sort' => $request->input('sort', 0),
            'menu_component' => $request->input('component', ''),
            'menu_redirect' => $request->input('redirect', ''),
            'menu_icon' => $request->input('icon', ''),
            'menu_title' => $request->input('label', ''),
            'name' => $request->input('subtitle', ''),
        ]);

        if ($permission) {
            return $this->retData([], '修改成功');
        } else {
            return $this->retData([], '修改失败', 202, 'error');
        }
    }

    public function assignPermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
        ]);

        $permissionIds = $request->input('permissions');

        if (!is_array($request->input('permissions'))) {
            $permissionIds = explode(',', $request->input('permissions'));
        }

        $role = Role::find($request->input('role_id'));
        $permissions = Permission::wherein('id', $permissionIds)->get();

        $res = $role->syncPermissions($permissions);

        if ($res->id) {
            return $this->retData($res->id);
        } else {
            return $this->retData([], '操作失败', 202);
        }
    }

    public function subTree(array $data, int $pid = 0)
    {
        $arr = [];
        foreach ($data as $val) {
            if ($pid == $val['pid']) {
                $newData = [
                    'key' => $val['id'],
//                    'auth' => $val['name'],
                    'label' => $val['menu_title'],
                    'subtitle' => $val['name'],
                    'path' => $val['menu_path'],
                    'component' => $val['menu_component'],
                    'sort' => $val['menu_sort'],
                    'redirect' => $val['menu_redirect'],
                    'icon' => $val['menu_icon'],
                    'pid' => $val['pid'],
                    'openType' => 1,
                    'type' => 1,
                ];
                $newData['children'] = $this->subTree($data, $val['id']);
                if (empty($newData['children'])) {
                    unset($newData['children']);
                }
                $arr[] = $newData;
            }
        }
        return $arr;
    }
}
