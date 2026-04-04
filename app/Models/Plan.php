<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'title',
        'date',
        'notes',
    ];
   public function destination()
{
    return $this->belongsTo(Destination::class, 'destinations_id', 'destinations_id');
}
    
}