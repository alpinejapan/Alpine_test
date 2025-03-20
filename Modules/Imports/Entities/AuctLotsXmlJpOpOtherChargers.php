<?php

namespace Modules\Imports\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctLotsXmlJpOpOtherChargers extends Model
{
    use HasFactory;
    protected $fillable = [
        'commission_value',
        'shipping_value',
        'marine_insurance_value',
        'inland_inspection_value',
        'top_sell',
        'new_arrival'
    ];
}
