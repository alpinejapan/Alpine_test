<?php

namespace Modules\SmallHeavy\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmallHeavy extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="small_heavy";
    protected $fillable=[
        'id',
        'category',
        'image',
        'title',
        'make',
        'model',
        'year_of_reg',
        'grade',
        'chassis',
        'serial',
        'yom',
        'kms',
        'hrs',
        'engine',
        'transmission',
        'fuel',
        'dimensions',
        'price',
        'price_ru',
        'price_jpy',
        'sell_points',
        'remarks',
        'is_active',
        'is_ru_market',
        'is_na_market',
        'hooks',
        'boom',
        'jib',
        'outrigger',
        'commission_value',
        'new_arrival',
        'deleted_at',
        'location'
      ];
      protected $dates=['deleted_at'];
}
