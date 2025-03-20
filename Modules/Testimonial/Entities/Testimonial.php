<?php

namespace Modules\Testimonial\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Testimonial\Entities\TestimonialTranslation;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $hidden = ['front_translate'];

    protected $appends = ['name', 'designation', 'comment'];

    protected static function newFactory()
    {
        return \Modules\Testimonial\Database\factories\TestimonialFactory::new();
    }

    public function translate(){
        return $this->belongsTo(TestimonialTranslation::class, 'id', 'testimonial_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(TestimonialTranslation::class, 'id', 'testimonial_id')->where('lang_code', front_lang());
    }

    public function getNameAttribute()
    {
        return $this->front_translate->name;
    }

    public function getDesignationAttribute()
    {
        return $this->front_translate->designation;
    }

    public function getCommentAttribute()
    {
        return $this->front_translate->comment;
    }
}
