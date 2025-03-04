<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluasi extends Model
{
    use HasFactory;

    protected $table = 'evaluasis';
    protected $primaryKey = 'id_evaluasi';
    public $timestamps = true; // Karena ada created_at & updated_at

    protected $fillable = [
        'id_evaluasi',
        'tanggal_terakhir_dilakukan',
        'tanggal_diperbarui',
    ];

    // Relasi ke NamaFileEval (One to Many)
    public function namaFileEval()
    {
        return $this->hasMany(NamaFileEval::class, 'id_evaluasi', 'id_evaluasi');
    }

    public function fileEval()
    {
        return $this->hasMany(FileEval::class, 'id_evaluasi', 'id_evaluasi');
    }

}
