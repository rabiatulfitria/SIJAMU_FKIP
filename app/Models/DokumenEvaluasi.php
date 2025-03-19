<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenEvaluasi extends Model
{
    use HasFactory;
    protected $table = 'dokumen_evaluasi';

    // Pendefinisian primarykey secara khusus. Karena default laravel berupa 'id'
    protected $primaryKey = 'id_dokumeneval';
    public $timestamps = true;

     /**
     * Atribut diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_dokumeneval',
        'id_evaluasi',
        'nama_dokumen',
        'id_prodi',
        'file_eval'
    ];
    
    public function evaluasi()
    {
        return $this->belongsTo(Evaluasi::class, 'id_evaluasi', 'id_evaluasi');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }
    
}
