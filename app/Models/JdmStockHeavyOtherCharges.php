<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JdmStockHeavyOtherCharges extends Model
{
    use HasFactory;
    protected $table='jdm_stock_heavy_other_charges';
    protected $fillable=[
      'jdm_heavy_id',
      'marine_insurance_value',
      'inland_inspection_value'
    ];
}
