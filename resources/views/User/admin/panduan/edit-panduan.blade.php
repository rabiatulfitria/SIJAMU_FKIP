@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center" style="margin-left: 15px;">File Panduan Pengguna SIJAMU FKIP</div>
        </div>
    @endsection

@section('content')
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit File Panduan</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.panduan.update', $panduan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label" for="nama_file">Nama File</label>
                            <input type="text" class="form-control" id="nama_file" name="nama_file"
                                value="{{ old('nama_file', $panduan->nama_file) }}" required placeholder="Masukkan Nama File Panduan">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="tahun">Tahun</label>
                            <input type="text" class="form-control" id="tahun" name="tahun"
                                value="{{ old('tahun', $panduan->tahun) }}" required placeholder="Masukkan Tahun">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Buka File:</label><br>
                            <a href="{{ asset('storage/' . $panduan->path) }}" target="_blank">{{ $panduan->nama_file }}</a>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="pdf">Ganti File PDF (opsional)</label>
                            <input type="file" class="form-control" id="pdf" name="pdf" accept="application/pdf">
                            <p class="form-text" style="color: #7ebcfe">Biarkan kosong jika tidak ingin mengganti file. Maksimum 5MB.</p>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('admin.panduan.index') }}'">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
