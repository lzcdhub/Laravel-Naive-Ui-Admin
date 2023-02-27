<?php

namespace Modules\News\Transformers;

use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['money_invest'] = $this->moneyInvestText($data['money_invest']);
        $data['time_limit'] = $this->timeLimitText($data['time_limit']);
        $data['interest_paymet'] = $this->interestPaymetText($data['interest_paymet']);
        $data['project_area'] = $this->area->name;
        foreach ($data as $key => $val) {
            empty($val) && $data[$key] = '';
        }
        return $data;
    }

    public function moneyInvestText($index)
    {
        if (empty($index)) {
            $index = '0';
        } else {
            $index = $index->value;
        }
        $data = [
            '0' => '其它',
            '1' => '基础设施',
            '2' => '房地产',
            '3' => '工商企业',
            '4' => '资金池',
            '5' => '其它',
        ];
        return $data[$index];
    }

    public function timeLimitText($index)
    {
        if (empty($index)) {
            $index = '0';
        } else {
            $index = $index->value;
        }
        $data = [
            '1' => '12个月以内',
            '2' => '12个月',
            '3' => '13-23个月',
            '4' => '24个月',
            '5' => '24个月以上'
        ];
        return $data[$index];
    }

    public function interestPaymetText($index)
    {
        if (empty($index)) {
            $index = '0';
        } else {
            $index = $index->value;
        }
        $data = [
            '1' => '按月付息',
            '2' => '按季付息',
            '3' => '半年付息',
            '4' => '按年付息',
            '5' => '到期付息'
        ];
        return $data[$index];
    }
}
