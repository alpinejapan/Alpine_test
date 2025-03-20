<?php

namespace Modules\Commercial\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commercial extends Model
{
    use HasFactory;
    protected $table="commercial";
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
        'new_arrival',
        'location'
      ];
}
