<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pakaian extends Model
{
    use HasFactory;

    protected $table = 'pakaian'; // Nama tabel di database

    protected $fillable = [
        'nama',
        'jenis_acara',
        'jenis_pakaian',
        'harga',
        'warna',
        'lokasi',
        'cuaca',
        'rating'
    ];
}
