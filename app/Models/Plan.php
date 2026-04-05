<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'hari',
        'jam',
        'kegiatan',
        'lokasi',
    ];

    /**
     * Relasi: Plan dimiliki oleh satu Destination
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
