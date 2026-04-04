<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    public function plans()
{
    return $this->hasMany(Plan::class);
}
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

