<?php

namespace Modules\Car\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Car\Entities\CarTranslation;
use Modules\Brand\Entities\Brand;
use App\Models\User;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $appends = ['title', 'description', 'video_description', 'address', 'seo_title', 'seo_description'];

    protected $hidden = ['front_translate'];

    protected static function newFactory()
    {
        return \Modules\Car\Database\factories\CarFactory::new();
    }

    public function dealer(){
        return $this->belongsTo(User::class, 'agent_id')->select('id', 'name', 'email', 'image');
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function translate(){
        return $this->belongsTo(CarTranslation::class, 'id', 'car_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(CarTranslation::class, 'id', 'car_id')->where('lang_code', front_lang());
    }

    public function getTitleAttribute()
    {
        return $this->front_translate->title;
    }

    public function getDescriptionAttribute()
    {
        return $this->front_translate->description;
    }

    public function getVideoDescriptionAttribute()
    {
        return $this->front_translate->video_description;
    }

    public function getAddressAttribute()
    {
        return $this->front_translate->address;
    }

    public function getSeoTitleAttribute()
    {
        return $this->front_translate->seo_title;
    }

    public function getSeoDescriptionAttribute()
    {
        return $this->front_translate->seo_description;
    }

}
