<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarInstitut extends Model
{
    use HasFactory;
    protected $table = 'standar_institusi';

    // Pendefinisian primarykey secara khusus. Karena default laravel berupa 'id'
    protected $primaryKey = 'id_standarinstitut'; //jangan lupa di database di buat Auto_Increment
    public $timestamps = true;

    /**
     * Atribut diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_standarinstitut',
        'id_penetapan',
        'namafile',
        'kategori',
        'id_prodi',
        'file'
    ];

    public function penetapan()
    {
        return $this->belongsTo(Penetapan::class, 'id_penetapan', 'id_penetapan');
    }

    public function prodi()
    {
      return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }
}
