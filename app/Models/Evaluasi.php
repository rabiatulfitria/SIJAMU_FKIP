<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluasi extends Model
{
    use HasFactory;

    protected $table = 'evaluasis';
    protected $primaryKey = 'id_evaluasi';
    protected $dates = ['tanggal_terakhir_dilakukan', 'tanggal_diperbarui'];
    public $timestamps = true; // Karena ada created_at & updated_at

    protected $fillable = [
        'tanggal_terakhir_dilakukan',
        'tanggal_diperbarui',
    ];

    // Relasi ke NamaFileEval (One to Many)
    public function namaFileEval()
    {
        return $this->hasMany(NamaFileEval::class, 'id_evaluasi', 'id_evaluasi');
    }

    public function fileEval()
    {
        return $this->hasMany(FileEval::class, 'id_evaluasi', 'id_evaluasi');
    }

    // Accessor untuk menampilkan format tanggal yang diinginkan
    public function getTanggalTerakhirDilakukanFormattedAttribute()
    {
        return $this->tanggal_terakhir_dilakukan->format('d/m/Y');
    }

    public function getTanggalDiperbaruiFormattedAttribute()
    {
        return $this->tanggal_diperbarui->format('d/m/Y');
    }
}
