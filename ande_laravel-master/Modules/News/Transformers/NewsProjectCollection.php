<?php

namespace Modules\News\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsProjectCollection extends ResourceCollection
{

    public static $wrap = 'result';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => parent::toArray($request),
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
