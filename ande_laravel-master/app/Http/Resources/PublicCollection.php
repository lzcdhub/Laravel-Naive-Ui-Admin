<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PublicCollection extends ResourceCollection
{
    public static $wrap = 'result';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection,
            'page' => $this->resource->currentPage(),
            'pageCount' => $this->resource->lastPage(),
            'pageSize' => $this->resource->perPage(),
            'itemCount' => $this->resource->total(),
        ];
    }

    public function with($request)
    {
        return [
            'code' => 200,
            'message' => 'ok',
            'type' => 'success'
        ];
    }

    public function paginationInformation()
    {
        return [];
    }

}
