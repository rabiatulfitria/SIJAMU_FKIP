<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peningkatan extends Model
{
    use HasFactory;

    protected $table = 'peningkatans';
    protected $primaryKey = 'id_peningkatan';

    /**
     * Atribut diisi secara massal
     *
     * @var array<int, string>
     */

     protected $fillable = [
        'id_peningkatan',
        'nama_dokumen',
        'bidang_standar',
        'tanggal_penetapan_baru',
        'file_peningkatan',
        'id_prodi',
     ];

     public function prodi()
     {
       return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
     }   

}
