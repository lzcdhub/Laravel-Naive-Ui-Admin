<?php

namespace Modules\News\Entities;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends BaseModel
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Modules\WxShop\Database\factories\IntegralFactory::new();
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public function updateData($id, $data, $model)
    {
        if($model->fillable){
            foreach ($data as $k => $v) {
                if (!in_array($k, $model->fillable)) {
                    unset($data[$k]);
                }
            }
        }
        return $model::where('id', $id)->update($data);
    }
}
