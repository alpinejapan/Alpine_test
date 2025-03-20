<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Page\Database\factories\AboutUsFactory::new();
    }

    protected $hidden = ['front_translate'];

    protected $appends = ['header', 'title','description','total_car','total_car_title','total_review','total_review_title'];

    public function translate(){
        return $this->belongsTo(AboutUsTranslation::class, 'id', 'about_us_id')->where('lang_code' , admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(AboutUsTranslation::class, 'id', 'about_us_id')->where('lang_code' , front_lang());
    }

    public function getHeaderAttribute()
    {
        return $this->front_translate->header;
    }

    public function getTitleAttribute()
    {
        return $this->front_translate->title;
    }

    public function getDescriptionAttribute()
    {
        return $this->front_translate->description;
    }

    public function getTotalCarAttribute()
    {
        return $this->front_translate->total_car;
    }

    public function getTotalCarTitleAttribute()
    {
        return $this->front_translate->total_car_title;
    }

    public function getTotalReviewAttribute()
    {
        return $this->front_translate->total_review;
    }

    public function getTotalReviewTitleAttribute()
    {
        return $this->front_translate->total_review_title;
    }






}
