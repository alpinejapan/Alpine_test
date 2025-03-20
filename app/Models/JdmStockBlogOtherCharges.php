<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JdmStockBlogOtherCharges extends Model
{
    use HasFactory;
    protected $table='jdm_stock_blog_other_charges';
    protected $fillable=[
      'jdm_blog_id',
      'marine_insurance_value',
      'inland_inspection_value'
    ];
}
