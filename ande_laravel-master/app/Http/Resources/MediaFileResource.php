<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['media_url'] = $this->getUrl();
        $data['created_at'] = Carbon::parse($data['created_at'])->addHour(8)->toDateTimeString();
        $data['size'] = convert($data['size']);
//        $data['use_num'] = $this->models;
        return $data;
    }
}
