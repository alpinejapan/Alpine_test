<?php

namespace Modules\Cars\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddSmallHeavyImages extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable=[
        'image',
        'category',
        'is_active'
    ];
}
