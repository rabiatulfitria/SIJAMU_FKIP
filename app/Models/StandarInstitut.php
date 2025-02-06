<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarInstitut extends Model
{
    use HasFactory;
    protected $table = 'standar_institusi';

    // Pendefinisian primarykey secara khusus. Karena default laravel berupa 'id'
    protected $primaryKey = 'standar_institut';

    /**
     * Atribut diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_standarinstitut',
        'nama_dokumenstandar',
        'kategori',
        'tanggal_ditetapkan',
        'program_studi',
        'files'
    ];
}
