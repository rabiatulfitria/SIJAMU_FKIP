<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
   use HasFactory;

   protected $table = 'tabel_prodi';
   protected $primaryKey = 'id_prodi';

   /**
    * Atribut diisi secara massal
    *
    * @var array<int, string>
    */

   protected $fillable = [
      'id_prodi',
      'nama_prodi',
   ];

   public function pengendalians()
   {
      return $this->hasMany(Pengendalian::class, 'id_prodi');
   }
   
   public function pelaksanaan_prodi()
   {
      return $this->hasMany(pelaksanaan_prodi::class, 'id_prodi');
   }

   public function peningkatans()
   {
      return $this->hasMany(Peningkatan::class, 'id_prodi');
   }
}
