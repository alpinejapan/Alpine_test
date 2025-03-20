<?php

namespace Modules\Cars\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cars extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="blog";
    protected $fillable=[
        'id',
        'category',
        'image',
        'title',
        'make',
        'brand',
        'model',
        'color',
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
        'price',
        'price_ru',
        'price_jpy',
        'sell_points',
        'remarks',
        'is_active',
        'is_ru_market',
        'is_na_market',
        'sr',
        'aw',
        'pw',
        'ps',
        'ab',
        'engine_type',
        'int_col',
        'abs',
        'has_video',
        'inside',
        'outside',
        'commission_value',
        'shipping_value',
        'new_arrival',
        'deleted_at',
        'location',
        'marine_insurance_value',
        'inland_inspection_value'
      ];
      protected $dates=['deleted_at'];
}
