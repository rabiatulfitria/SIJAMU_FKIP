<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaFileEval extends Model
{
    use HasFactory;

    protected $table = 'nama_file_eval';
    protected $primaryKey = 'id_nfeval';
    public $timestamps = true;

    protected $fillable = [
        'nama_fileeval',
        'id_prodi',
        'id_evaluasi',
    ];

    // Relasi ke Evaluasi (Many to One)
    public function evaluasi()
    {
        return $this->belongsTo(Evaluasi::class, 'id_evaluasi', 'id_evaluasi');
    }

    // Relasi ke FileEval (One to Many)
    public function fileEval()
    {
        return $this->hasMany(FileEval::class, 'id_nfeval', 'id_nfeval');
    }

    public function prodi()
    {
      return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }

}
