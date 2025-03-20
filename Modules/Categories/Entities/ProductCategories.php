<?php

namespace Modules\Categories\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'text',
        'is_active'
    ];
}
