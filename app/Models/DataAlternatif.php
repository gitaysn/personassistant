<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAlternatif extends Model
{
    use HasFactory;
    protected $table = 'dataalternatif';
    protected $fillable = ['nama_alternatif', 'subkriteria_id', 'gambar'];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }

    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class, 'subkriteria_id');
    }

    public function nilai()
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id', 'id');
    }
    
}
