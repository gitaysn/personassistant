<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAlternatif extends Model
{
    use HasFactory;

    protected $table = 'nilai_alternatif'; // Sesuaikan dengan nama tabel di database

    protected $fillable = ['alternatif_id', 'nilai'];

    public function alternatif()
    {
        return $this->belongsTo(DataAlternatif::class, 'alternatif_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function nilai()
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id'); // Hubungkan ke Penilaian
    }
}
