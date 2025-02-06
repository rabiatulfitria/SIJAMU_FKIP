<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Panduanpengguna extends Controller
{
    private $pdfPath = 'Panduanpengguna/uploaded.pdf';

    public function index()
    {
        $pdfExists = Storage::exists($this->pdfPath);
        return view('halamanLogin.Panduanpengguna', ['pdfExists' => $pdfExists, 'pdfPath' => $this->pdfPath]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:2048',
        ]);

        if (Storage::exists($this->pdfPath)) {
            Storage::delete($this->pdfPath);
        }

        $path = $request->file('pdf')->storeAs('Panduanpengguna', 'uploaded.pdf');

        return redirect()->back()->with('success', 'PDF berhasil diunggah!');
    }

}
