<?php

namespace Modules\DeliveryCharges\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCharge extends Model
{
    use HasFactory;
    protected $fillable=[
      'id',
      'country_name',
      'rate',
      'roro',
      'container'
    ];
}
