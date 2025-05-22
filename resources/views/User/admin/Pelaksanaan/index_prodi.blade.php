@extends('User.admin.Pelaksanaan.sidebar_prodi')
@section('tabel-unggah-dokumen')
    @if (Auth::user() &&
            (Auth::user()->role->role_name == 'Admin' ||
                Auth::user()->role->role_name == 'JMF' ||
                Auth::user()->role->role_name == 'Dosen' ||
                Auth::user()->role->role_name == 'Koordinator Prodi'))
        <a id="tambahDokumenBtnp" href="/tambahdata-dokumen-pelaksanaan-prodi" class="btn btn-primary mb-3">Tambah Dokumen</a>
        <input type="hidden" id="isFormulirKepuasan" value="0">
        <!--jika menu yang diklik adalah Formulir Kepasan Mhs, teks button berubah-->
    @endif
    <!-- Tabel yang akan ditampilkan -->
    <div id="DatatablesRenstraProgramStudinya">
        <!-- Tabel Renstra Program Studi di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen Renstra</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($renstraProgramStudi as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <div id="DatatablesKinerjaProgramStudinya">
        <!-- Tabel Kinerja Program Studi di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen Laporan Kinerja</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanKinerjaProgramStudi as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> --}}

    <div id="DatatablesKurikulum">
        <!-- Tabel Dokumen Kurikulum di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen Kurikulum</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($dokumenKurikulum as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="DatatablesRPS">
        <!-- Tabel RPS di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen RPS</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($rps as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="DatatablesMonitoring">
        <!-- Tabel Monitoring di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen Monev MBKM</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($monitoringMbkm as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ url('/edit-dokumen-pelaksanaan/' . $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form action="{{ route('deletePelaksanaan', $document->id_plks_prodi) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="DatatablesCPL">
        <!-- Tabel CPL di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen CPL</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($cpl as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="DatatablesPanduanRPS">
        <!-- Tabel Pandua RPS di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen Panduan RPS</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($panduanRps as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="DatatablesPanduanMutuSoal">
        <!-- Tabel Panduan Mutu Soal di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen Panduan Mutu Soal</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($panduanMutuSoal as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="DatatablesPanduanKisi">
        <!-- Tabel Panduan Kisi Soal di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen Panduan Kisi Kisi Soal</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($panduanKisiKisi as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <!-- Link ke dokumen -->
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="DatatablesFormulirKepuasan">
        <!-- Tabel link Formulir Kepuasan Mhs di sini -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Tautan Formulir</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($formulirKepuasan as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                @if (filter_var($document->file, FILTER_VALIDATE_URL))
                                    <a href="{{ $document->file }}" class="badge bg-label-info me-1" target="_blank"
                                        class="bi bi-link-45deg">Buka Tautan Formulir</a>
                                @else
                                    <p>Masih dalam proses</p>
                                @endif
                            @endif
                        </td>
                        <td>{{ $document->periode_tahunakademik }}</td>
                        @if (Auth::user() &&
                                (Auth::user()->role->role_name == 'Admin' ||
                                    Auth::user()->role->role_name == 'JMF' ||
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi-Form', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDeleteForm({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="DatatablesMonitoringLayanan">
        <!-- Tabel Monev Ketercapana Standar Kemahasiswaan -->
        <table class="table table-bordered custom-table-sm">
            <thead class="table-purple">
                <tr>
                    <th>No.</th>
                    <th>Nama Dokumen</th>
                    <th>Program Studi</th>
                    <th>Dokumen Monev Standar Layanan Kemahasiswaan</th>
                    <th>Periode/TA</th>
                    @if (Auth::user() &&
                            (Auth::user()->role->role_name == 'Admin' ||
                                Auth::user()->role->role_name == 'JMF' ||
                                Auth::user()->role->role_name == 'Dosen' ||
                                Auth::user()->role->role_name == 'Koordinator Prodi'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($monitoringKemahasiswaan as $loop => $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $document->namafile }}</td>
                        <td>{{ $document->prodi->nama_prodi }}</td>
                        <td>
                            @if ($document->file)
                                <a href="{{ route('dokumenpelaksanaanProdi.tampil', ['id_plks_prodi' => $document->id_plks_prodi, 'namafile' => $document->namafile, 'file' => basename($document->file)]) }}"
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
                                    Auth::user()->role->role_name == 'Dosen' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <td>
                                <a href="{{ route('editPelaksanaanProdi', $document->id_plks_prodi) }}"
                                    class="btn btn-warning btn-sm btn-ubah-pelaksanaan">Ubah</a>
                                <form id="delete-form-{{ $document->id_plks_prodi }}"
                                    action="{{ route('deletePelaksanaanProdi', $document->id_plks_prodi) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $document->id_plks_prodi }})">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editButtons = document.querySelectorAll(".btn-ubah-pelaksanaan");
    
            editButtons.forEach(function (btn) {
                btn.addEventListener("click", function (e) {
                    e.preventDefault(); // cegah redirect langsung
    
                    const originalHref = btn.getAttribute("href");
    
                    // Ambil menuProdi dari tombol Tambah Dokumen
                    const tambahDokumenBtnp = document.getElementById("tambahDokumenBtnp");
                    const menuProdi = tambahDokumenBtnp?.getAttribute("data-menuprodi") || "";
    
                    // Susun ulang URL dengan query ?menuprodi=...
                    const url = new URL(originalHref, window.location.origin);
                    if (menuProdi) {
                        url.searchParams.set("menuprodi", menuProdi);
                    }
    
                    // Redirect ke URL baru
                    window.location.href = url.toString();
                });
            });
        });
    </script>
    


@endsection
