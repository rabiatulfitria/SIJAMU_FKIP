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
    public $timestamps = true;

     /**
     * Atribut diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_dokspmi',
        'namafile',
        'kategori',
        'id_penetapan',
        'file'
    ];
    
    public function penetapan()
    {
        return $this->belongsTo(Penetapan::class, 'id_penetapan', 'id_penetapan');
    }
}
