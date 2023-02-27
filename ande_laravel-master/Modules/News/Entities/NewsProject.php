<?php

namespace Modules\News\Entities;

use App\Models\Area;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\News\ModelFilters\NewsProjectFilter;
use Illuminate\Database\Eloquent\Casts\AsStringable;

class NewsProject extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
        'category_id',
        'money_invest',
        'project_area',
        'project_rating',
        'status_f',
        'title',
        'time_limit',
        'offering_size',
        'interest_start_date',
        'interest_paymet',
        'interest_pay_date',
        'use_funds',
        'publisher_introduce',
        'guarantee_introduce',
        'area_introduce',
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'status_f' => AsStringable::class,
        'money_invest' => AsStringable::class,
        'time_limit' => AsStringable::class,
        'interest_paymet' => AsStringable::class,
    ];

    public function modelFilter()
    {
        return $this->provideFilter(NewsProjectFilter::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class,'project_area');
    }
}
