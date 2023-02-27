<?php

namespace App\Models;

use DateTimeInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Modules\EasyWechat\Entities\WxMember;
use Modules\WxShop\Entities\Integral;
use Modules\WxShop\Entities\ExchangeShop;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Plank\Mediable\Mediable;

class Member extends model
{
    use HasFactory, SoftDeletes, HasApiTokens, Filterable, Mediable;

    protected $hidden = [
        'password',
    ];

    protected $fillable = ['username', 'password', 'real_name', 'gender', 'phone', 'group_id'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'gender' => AsStringable::class
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public function integral()
    {
        return $this->hasMany(Integral::class);
    }

    public function exchange()
    {
        return $this->hasMany(ExchangeShop::class);
    }

    public function wxMember()
    {
        return $this->hasOne(WxMember::class);
    }

    public function genderText(): Attribute
    {
        return new Attribute(
            get: fn($value) => $this->getGenderParam($this->gender),
        );
    }

    protected function actualIntegral(): Attribute
    {
        $integral = $this->integral()->orderBY('id', 'desc')->value('new_integral');
        return new Attribute(
            get: fn() => is_null($integral) ? 0 : $integral,
        );
    }

    protected function avatarurl() :Attribute
    {
        $url= '';
        if($this->hasMedia('avatar')){
            $url = $this->getMedia('avatar')->last()->getUrl();
        }
        return new Attribute(
            get: fn () => $url,
        );
    }

    protected function exchangeNum(): Attribute
    {
        return new Attribute(
            get: fn() => $this->exchange()->count(),
        );
    }

    protected function wxBinding(): Attribute
    {
        return new Attribute(
            get: fn() => $this->wxMember()->count(),
        );
    }

    public function updateData($id, $data, $model)
    {
        foreach ($data as $k => $v) {
            if (!in_array($k, $model->fillable)) {
                unset($data[$k]);
            }
        }
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        }
        return $model::where('id', $id)->update($data);
    }

    public function createData($data, $model)
    {
        empty($data['password']) && $data['password'] = '123456';
        $data['password'] = Hash::make($data['password']);
        return $model::create($data);
    }

    public function getGenderParam($value, $type = 'get')
    {
        $data = ['保密', '男', '女'];
        return $data[$value->value];
    }
}
