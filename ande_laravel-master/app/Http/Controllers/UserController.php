<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Plank\Mediable\Facades\MediaUploader;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('pageSize', 10);
        $data = User::filter($request->all())->with('roles:id,name')->orderByDesc('id')->paginate($limit);
        return new UserCollection($data);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        //TODO 前端权限判断的数据
//        if ($user->hasRole('SuperAdmin')) {
//            $user->permissions = Permission::all()->toArray();
//        } else {
//            $user->permissions = $user->getAllPermissionData();
//        }

        $user->permissions = ['*'];
        $user->role_names = $user->getRoleNames();

        return $this->retData($user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5'
        ]);

        $hasRole = User::where([
            ['name', '=', $request->input('name')],
        ])->first();

        if ($hasRole) {
            return $this->retData([], $request->input('name') . ' 名称重复', 202, 'error');
        }

        $add = new User();
        $add->name = $request->input('name');
        $add->email = $request->input('email', '');
        if (empty($request->input('password'))) {
            $add->password = Hash::make('admin123456');
        } else {
            $add->password = Hash::make($request->input('password'));
        }
        $add->save();

        $add->assignRole($request->input('roleValue', []));

        if ($add->id) {
            return $this->retData($add->id, '添加成功');
        } else {
            return $this->retData([], '添加失败', 202, 'error');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $param = $request->all();

        $hasRole = User::where([
            ['name', '=', $param['name']],
            ['id', '<>', $param['id']],
        ])->first();

        if ($hasRole) {
            return $this->retData([], $param['name'] . '名称重复', 202, 'error');
        }

        $userMod = User::find($id);
        !empty($param['password']) && $userMod->password = Hash::make($param['password']);
        $userMod->name = $param['name'];
        $userMod->email = $param['email'];
        $userMod->real_name = $param['real_name'] ?? '';
        $userMod->phone = $param['phone'] ?? '';
        $userMod->address = $param['address'] ?? '';
        $userMod->save();

        if (isset($param['roleValue'])) {
            $userMod->syncRoles($param['roleValue']);//更新用户角色
        }

        return $this->retData([]);
    }

    public function destroy($id)
    {
        $deleted = User::destroy($id);

        if ($deleted) {
            return $this->retData([$id], '删除成功');
        } else {
            return $this->retData([], '删除失败', '202');
        }
    }

    public function login(Request $request)
    {
        if (empty($request->username)) {
            return response([
                'code' => -1,
                'message' => '账户名错误',
            ], 200);
        }

        $user = User::where('name', $request->username)->first();

        if (empty($user)) {
            return response([
                'code' => -1,
                'message' => '账户名错误'
            ], 200);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'code' => -1,
                'message' => '密码错误'
            ], 200);
        }

        if ($user->hasRole('SuperAdmin')) {
            $abilities = collect(['*']);
        } else {
            $abilities = $user->getAllPermissions()->pluck('name');
        }

        //$user->tokens()->delete();
        $token = $user->createToken($user->name,$abilities->toArray(),Carbon::now()->addMonth()->toDate());

        $response = [
            'code' => 200,
            'message' => '登录成功',
            'result' => [
                'token' => 'Bearer ' . $token->plainTextToken
            ],
            'type' => 'success'
        ];

        return response($response, 200);
    }

    public function uploadFile(Request $request)
    {
        $prefix = $request->input('disk', 'unknown') . '/';

        $media = MediaUploader::fromSource($request->file('files'))
            ->useFilename($this->fileName())
            ->toDirectory($prefix . date('Y') . date('/m') . date('/d'))
            ->upload();
        return $this->retData([
            'photo' => $media->getUrl(),
            'data' => $media
        ]);
    }

    public function fileName()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $string = time();
        for ($len = 8; $len >= 1; $len--) {
            $position = rand() % strlen($chars);
            $position2 = rand() % strlen($string);
            $string = substr_replace($string, substr($chars, $position, 1), $position2, 0);
        }
        return $string;
    }

    public function logOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->retData();
    }

}
