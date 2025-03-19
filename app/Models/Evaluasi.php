<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Evaluasi extends Model
{
    use HasFactory;

    protected $table = 'evaluasis';
    protected $primaryKey = 'id_evaluasi';
    public $timestamps = true; // Karena ada created_at & updated_at

    protected $fillable = [
        'id_evaluasi',
        'tanggal_terakhir_dilakukan',
        'tanggal_diperbarui',
    ];

    // Mutator untuk mengatur format tanggal saat diambil dari database
    public function getTanggalTerakhirDilakukanAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    // Mutator untuk memastikan format tanggal tetap Y-m-d saat disimpan ke database
    public function setTanggalTerakhirDilakukanAttribute($value)
    {
        $this->attributes['tanggal_terakhir_dilakukan'] = Carbon::parse($value)->format('Y-m-d');
    }

    // Mutator untuk mengatur format tanggal saat diambil dari database
    public function getTanggalDiperbaruiAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    // Mutator untuk memastikan format tanggal tetap Y-m-d saat disimpan ke database
    public function setTanggalDiperbaruiAttribute($value)
    {
        $this->attributes['tanggal_diperbarui'] = Carbon::parse($value)->format('Y-m-d');
    }
}
