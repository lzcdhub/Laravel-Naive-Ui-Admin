<?php
namespace Modules\News\ModelFilters;

use EloquentFilter\ModelFilter;

class NewsProjectFilter extends ModelFilter
{
    public $relations = [];

    public function title($val)
    {
        return $this->whereLike('title',$val);
    }

    public function createAt($val)
    {
        $date = substr($val,0,10);
        $dateArr = [date('Y-m-d 00:00:00',$date),date('Y-m-d 23:59:59',$date)];
        return $this->whereBetween('created_at',$dateArr);
    }

    public function statusF($val)
    {
        return $this->where('status_f',$val);
    }
}
