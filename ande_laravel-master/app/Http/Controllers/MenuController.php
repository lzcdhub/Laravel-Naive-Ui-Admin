<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuController extends Controller
{
    public function menus(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('SuperAdmin')) {
            $permissions = Permission::all();
        } else {
            $permissions = $user->getAllPermissions();
        }

        //TODO 菜单很多的话 把表结构换成 nestedset
        $menusData = $this->subTree($permissions->sortBy('menu_sort')->toArray());

        return $this->retData($menusData);
    }

    public function subTree(array $data, int $pid = 0)
    {
        $arr = [];
        foreach ($data as $val) {
            if ($pid == $val['pid']) {
                $newData = [
                    'name' => $val['name'],
                    'path' => $val['menu_path'],
                    'component' => $val['menu_component'],
                    'redirect' => $val['menu_redirect'],
                    'meta' => [
                        'icon' => $val['menu_icon'],
                        'title' => $val['menu_title'],
                        'sort' => $val['menu_sort'],
                    ],
                ];
                $newData['children'] = $this->subTree($data, $val['id']);
                $arr[] = $newData;
            }
        }
        return $arr;
    }

}
