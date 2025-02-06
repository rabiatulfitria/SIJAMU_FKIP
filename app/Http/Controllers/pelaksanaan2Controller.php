<?php

namespace App\Http\Controllers;

use App\Models\pelaksanaan_fakultas;

// use Illuminate\Http\Request;

class pelaksanaan2Controller extends Controller
{
    public function index()
    {
        return view('User.admin.Pelaksanaan.index_fakultas');
    }

    public function fakultas(){
        //mengambil data dari database melalui model Pelaksanaan dan mengembalikannya dalam format JSON(teks).
        $data=pelaksanaan_fakultas::all();
        return response()->json(['pelaksanaan_fakultas'=>$data]);
    }

}
