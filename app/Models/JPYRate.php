<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JPYRate extends Model
{
    use HasFactory;
    protected $table = 'j_p_y_rates';
    protected $fillable=['yen_rate'];
}
