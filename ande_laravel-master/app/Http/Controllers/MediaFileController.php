<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaFileCollection;
use Illuminate\Http\Request;
use Plank\Mediable\Media;

class MediaFileController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('pageSize', 10);
        $files = Media::orderBy('id', 'desc')->paginate($limit);
        return new MediaFileCollection($files);
    }

    public function destroy($id)
    {
        $media = Media::where('id',$id)->first();
        $deleted = $media->delete();
        if ($deleted) {
            return $this->retData([$id], '删除成功');
        } else {
            return $this->retData([], '删除失败', '202');
        }
    }
}
