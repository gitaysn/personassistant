<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizHistory extends Model
{
    use HasFactory;

    protected $table = 'quiz_histories';

    protected $fillable = [
        'data_kuisioner',
        'hasil_rekomendasi',
    ];

    protected $casts = [
        'data_kuisioner' => 'array',
        'hasil_rekomendasi' => 'array',
    ];
}
