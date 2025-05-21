<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;
    protected $table = 'datakriteria'; // Sesuaikan dengan nama tabel di database

    protected $fillable = ['kode_kriteria', 'nama_kriteria', 'bobot', 'jenis']; // Sesuaikan dengan kolom yang ada di tabel

    public function subkriteria()
    {
        return $this->hasMany(Subkriteria::class, 'kriteria_id'); // Pastikan foreign key sesuai dengan tabel subkriteria
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'kriteria_id');
    }

}
