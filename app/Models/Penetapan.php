<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    // Mutator untuk mengatur format tanggal saat diambil dari database
    public function getTanggalDitetapkanAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    // Mutator untuk memastikan format tanggal tetap Y-m-d saat disimpan ke database
    public function setTanggalDitetapkanAttribute($value)
    {
        $this->attributes['tanggal_ditetapkan'] = Carbon::parse($value)->format('Y-m-d');
    }
}
