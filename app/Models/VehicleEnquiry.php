<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleEnquiry extends Model
{
    protected $table="vehicle_enquiries";
    protected $gaurded=[];
    use HasFactory;
}

