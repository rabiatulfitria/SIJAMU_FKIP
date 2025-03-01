<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penetapan extends Model
{
  protected $table = 'penetapans';
  protected $primaryKey = 'id_penetapan';
  public $timestamps = true;

  protected $fillable = [
    'id_penetapan',
    'tanggal_ditetapkan',
  ];

  public function dokumenspmi()
  {
    return $this->hasMany(DokumenSPMI::class, 'id_penetapan');
  }
}
