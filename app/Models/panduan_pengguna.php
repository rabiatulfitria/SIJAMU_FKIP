<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class panduan_pengguna extends Model
{
    use HasFactory;

    protected $table = 'panduan_penggunas';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_file', 'tahun', 'path'];

}
