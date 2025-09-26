<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'status_helm',
        'kondisi_pekerja',
        'status_terbaring',
        'latitude',
        'longitude',
        'telegram_sent',
    ];

    protected $casts = [
        'telegram_sent' => 'boolean', // penting supaya JSON kirim boolean
    ];
}


