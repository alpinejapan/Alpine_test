<?php

namespace Modules\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Brand\Entities\BrandTranslation;

class ModelsCars extends Model
{
    protected $fillable=[
      'image',
      'category',
      'model'
    ];
    public function getBrand(){
      return  $this->hasOne(BrandTranslation::class,'brand_id','brand_id');
    }
    use HasFactory;
}
