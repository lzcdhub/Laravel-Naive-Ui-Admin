<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use function PHPUnit\Framework\isInstanceOf;

class MemberFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function username($v)
    {
        return $this->whereLike('username', $v);
    }

    public function combination($v)
    {
        if (is_numeric($v)) {
            return $this->where('id', settype($v, 'int'));
        }
        return $this->whereLike('username', $v);
    }

    public function createAt($val)
    {
        $date = substr($val, 0, 10);
        $dateArr = [date('Y-m-d 00:00:00', $date), date('Y-m-d 23:59:59', $date)];
        return $this->whereBetween('created_at', $dateArr);
    }

    public function phone($v)
    {
        return $this->whereLike('phone', $v);
    }

    public function realName($v)
    {
        return $this->whereLike('real_name', $v);
    }

}
