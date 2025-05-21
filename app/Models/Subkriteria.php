<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    use HasFactory;
    protected $table = 'subkriteria'; // Pastikan Laravel menggunakan nama tabel yang benar
    protected $primaryKey = 'id'; // Jika primary key bukan 'id'

    protected $fillable = ['nama_subkriteria', 'nilai', 'kriteria_id']; // Sesuaikan dengan kolom tabel

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'subkriteria_id');
    }

    public function dataAlternatif()
    {
        return $this->hasMany(DataAlternatif::class, 'subkriteria_id');
    }
}
