<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;
    protected $table = 'penilaian';
    protected $fillable = ['alternatif_id', 'kriteria_id', 'subkriteria_id'];

    protected $casts = [
        'nilai' => 'array', // Pastikan nilai disimpan sebagai array dalam database
    ];

    public function alternatif()
    {
        return $this->belongsTo(DataAlternatif::class, 'alternatif_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class, 'subkriteria_id');
    }
}
