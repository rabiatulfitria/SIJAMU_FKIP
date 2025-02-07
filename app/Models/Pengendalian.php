<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengendalian extends Model
{
    use HasFactory;

    protected $table = 'pengendalians';
    protected $primaryKey = 'id_pengendalian';

    /**
     * Atribut diisi secara massal
     *
     * @var array<int, string>
     */

  protected $fillable = [
    'id_pengendalian',
    'nama_dokumen',
    'tahun',
    'file_rtm',
    'file_rtl',
    'id_prodi',
  ];

  public function prodi()
  {
    return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
  }

}
