<?php

namespace App\Http\Controllers\AdminPro;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Appstract\Opcache\OpcacheFacade as OPcache;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            dd($validator->messages());
        }


        $user = User::find(17);


        $permissions2 = $user->getDirectPermissions();
        $permissions3 = $user->getPermissionsViaRoles();


        dd($user->getAllPermissions()->pluck('name'));
        dd(Carbon::now()->toDateTimeString());
    }

    public function admin()
    {
        return 123;
    }

    public function opcache($type)
    {
        echo '<pre>';
        if ($type == 'config') {
            var_dump('getConfig');
            var_dump(OPcache::getConfig());
        } elseif ($type == 'status') {
            var_dump('getStatus');
            var_dump(OPcache::getStatus());
        } elseif ($type == 'compile') {
            var_dump('compile');
            var_dump(OPcache::compile(true));
        } elseif ($type == 'clear') {
            var_dump('clear');
            var_dump(OPcache::clear());
        }
    }

    public function phpinfo()
    {
        phpinfo();
    }

}
