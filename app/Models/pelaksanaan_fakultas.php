<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelaksanaan_fakultas extends Model
{
    use HasFactory;
    protected $table = 'pelaksanaan_fakultas';

    // Pendefinisian primarykey secara khusus. Karena default laravel berupa 'id'
    protected $primaryKey = 'id_plks_fklts';

    /**
     * Atribut diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_plks_fklts',
        'namafile',
        'periode_tahunakademik',
        'id_kategori',
        'file'
    ];
    
    public function kategori()
    {
      return $this->belongsTo(kategori::class, 'id_kategori', 'id_kategori');
    }
    
}
