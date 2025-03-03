<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileEval extends Model
{
    use HasFactory;

    protected $table = 'file_eval';
    protected $primaryKey = 'id_feval';
    public $timestamps = true;

    protected $fillable = [
        'file',
        'id_nfeval',
    ];

    // Relasi ke NamaFileEval (Many to One)
    public function namaFileEval()
    {
        return $this->belongsTo(NamaFileEval::class, 'id_nfeval', 'id_nfeval');
    }

    public function evaluasi()
    {
        return $this->belongsTo(Evaluasi::class, 'id_evaluasi', 'id_evaluasi');
    }
}
