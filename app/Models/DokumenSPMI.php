<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenSPMI extends Model
{
    use HasFactory;
    protected $table = 'dokumen_spmi';

    // Pendefinisian primarykey secara khusus. Karena default laravel berupa 'id'
    protected $primaryKey = 'id_dokspmi';

     /**
     * Atribut diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_dokspmi',
        'nama_dokumenspmi',
        'kategori',
        'tanggal_ditetapkan',
        'files'
    ];
}
