@extends('User.admin.Pelaksanaan.sidebar_fakultas')
@section('tabel-unggah-dokumen')
    @if (Auth::user() &&
            (Auth::user()->role->role_name == 'Admin' ||
                Auth::user()->role->role_name == 'JMF' ||
                Auth::user()->role->role_name == 'Koordinator Prodi'))
        <a href="/tambah-dokumen-pelaksanaan-fakultas" class="btn btn-primary mb-3">Tambah Dokumen</a>
    @endif

    <div id="DatatablesRenstraProgramStudinya">
        <!-- Tabel Renstra Fakultas di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Dokumen Renstra Fakultas</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($renstraFakultas as $index => $document)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><a href="{{ asset('storage/' . $document->files) }}"
                            target="_blank">{{ $document->namafile }}</a> 
                        <td>{{ $document->periode_tahunakademik }}</td>
                        
                        @if (Auth::user() &&
                                (Auth::user()->role->role_name == 'Admin' ||
                                    Auth::user()->role->role_name == 'JMF' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanFakultas', $document->id_plks_fklts) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('deletePelaksanaanFakultas', $document->id_plks_fklts) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_fklts }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="DatatablesLaporanKinerjaFakultas">
        <!-- Tabel LaKin Fakultas di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Dokumen Laporan Kinerja Fakultas</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanKinerjaFakultas as $index => $document)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td><a href="{{ asset('storage/' . $document->file) }}" target="_blank">Buka Dokumen</a></td>
                        <td>{{ $document->periode_tahunakademik }}</td>

                        @if (Auth::user() &&
                                (Auth::user()->role->role_name == 'Admin' ||
                                    Auth::user()->role->role_name == 'JMF' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanFakultas', $document->id_plks_fklts) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form id="delete-form-{{ $document->id_plks_fklts }}"
                                    action="{{ route('deletePelaksanaanFakultas', $document->id_plks_fklts) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_fklts }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
