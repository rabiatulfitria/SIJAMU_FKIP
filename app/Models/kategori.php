<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
 
    /**
     * Atribut diisi secara massal
     *
     * @var array<int, string>
     */
 
    protected $fillable = [
       'id_kategori',
       'nama_kategori',
    ];
  
    public function pelaksanaan_prodi()
    {
       return $this->hasMany(pelaksanaan_prodi::class, 'id_kategori');
    }

    public function pelaksanaan_fakultas()
    {
        return $this->hasMany(pelaksanaan_fakultas::class, 'id_kategori');
    }
}
