<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $primaryKey = 'destinations_id'; 

    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'title',
        'image',
        'departure_date',
        'budget',
        'duration',
        'is_completed'
    ];

}

