<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait,SoftDeletes;

    protected $table = 'news_categories';

    protected $fillable = ['name'];

    public function news()
    {
        return $this->hasMany(NewsInfo::class);
    }

    public function project()
    {
        return $this->hasMany(NewsProject::class);
    }

}
