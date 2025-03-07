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
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($renstraFakultas as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanFakultas.tampil', ['id_plks_fklts' => $document->id_plks_fklts, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
                                    class="badge bg-label-info me-1" target="_blank">
                                    <i class="bi bi-link-45deg">Buka Dokumen</i>
                                </a>
                            @else
                                <p>Masih dalam proses</p>
                            @endif
                        </td>
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
    <div id="DatatablesLaporanKinerjaFakultas">
        <!-- Tabel LaKin Fakultas di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Dokumen Laporan Kinerja Fakultas</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanKinerjaFakultas as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanFakultas.tampil', ['id_plks_fklts' => $document->id_plks_fklts, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
                                    class="badge bg-label-info me-1" target="_blank">
                                    <i class="bi bi-link-45deg">Buka Dokumen</i>
                                </a>
                            @else
                                <p>Masih dalam proses</p>
                            @endif
                        </td>
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
