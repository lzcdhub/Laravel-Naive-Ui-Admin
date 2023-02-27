<?php

namespace Modules\News\Entities;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\News\ModelFilters\NewsInfoFilter;

class NewsInfo extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = ['title', 'category_id', 'ftitle', 'smalltext', 'writer', 'befrom', 'diggtop', 'newstext', 'user_id', 'status_f'];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'status_f' => AsStringable::class
    ];

    public function modelFilter()
    {
        return $this->provideFilter(NewsInfoFilter::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
