<?php

namespace App\Http\Controllers;

use App\Models\Timjamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class timjamuController extends Controller
{
    public function index()
    {
        $jamutims = Timjamu::all();
        return view('User.admin.TimJMF.timjamu', compact('jamutims'));
    }

    public function create()
    {
        return view('User.admin.TimJMF.tambahTimjamu');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'nama' => 'required',
            'email' => 'required|email|unique:jamutims',
            'jabatan' => 'required',
        ]);
        
        $dataBaru = new TimJamu;
        $dataBaru->nip = $request['nip'];
        $dataBaru->nama = $request['nama'];
        $dataBaru->email = $request['email'];
        $dataBaru->jabatan = $request['jabatan'];
        $dataBaru->save();

        Alert::success('Selesai', 'Tim JAMU berhasil ditambahkan.');
        return redirect()->route('TimJAMU');
    }

    public function edit( String $id)
    {
        $data = TimJamu::where('id',$id)->first();
        return view('User.admin.TimJMF.editTimjamu', [
            'oldData' => $data
        ]);
    }

    public function update(Request $request, String $id)
    {
        $dataUpdate = TimJamu::find($id);

        $request->validate([
            'nip' => 'required',
            'nama' => 'required',
            'email' => 'required|email',
            'jabatan' => 'required',
        ]);

        $dataUpdate->nip = $request['nip'];
        $dataUpdate->nama = $request['nama'];
        $dataUpdate->email = $request['email'];
        $dataUpdate->jabatan = $request['jabatan'];
        $dataUpdate->save();

        Alert::success('Selesai', 'Tim JAMU berhasil diperbarui.');
        return redirect()->route('TimJAMU');
    }

    public function destroy(String $id)
    {
        $dataDelete = TimJamu::findOrfail($id);
        $dataDelete->delete();

        Alert::success('Selesai', 'Tim JAMU berhasil dihapus.');
        return redirect()->route('TimJAMU');
    }
}
