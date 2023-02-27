<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Area extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('ancient', function (Builder $builder) {
            $builder->where('id', '!=', 0);
        });
    }

    public function parent()
    {
        return $this->belongsTo(Area::class, 'pid');
    }

    public function children()
    {
        return $this->hasMany(Area::class, 'pid');
    }

    protected function childrenNum(): Attribute
    {
        return new Attribute(
            get: fn($value) => $this->children()->count(),
        );
    }

    /**
     * 格式化地区 （山西省 / 太原市 / 清徐县）
     * @return Attribute
     */
    protected function getAllName(): Attribute
    {
        $newName = $this->name;
        if ($parent = $this->parent) {
            $newName = $parent->name . ' / ' . $newName;
            if ($parent = $parent->parent) {
                $newName = $parent->name . ' / ' . $newName;
            }
        }
        return new Attribute(
            get: fn($value) => $newName,
        );
    }

//    public function getParents($model)
//    {
//        static $list = [];
//        $list[] = $model;
//        if ($model->parent) {
//            $this->getParents($model->parent);
//        }
//        return $list;
//    }
}
