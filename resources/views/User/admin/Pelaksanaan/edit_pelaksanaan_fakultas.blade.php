@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center" style="margin-left: 15px;">Ubah Data Dokumen Pelaksanaan (Fakultas)</div>
        </div>
    @endsection

    @section('content')
        <div class="row">

            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $menu ?? 'Renstra Fakultas' }}</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updatePelaksanaanFakultas', $oldData->id_plks_fklts) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Nama Dokumen -->
                            <div class="mb-3">
                                <label class="form-label" for="bx bx-file">Nama Dokumen</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-file"></i></span>
                                    <input type="text" class="form-control" id="bx bx-file" name="namafile" placeholder="Nama Dokumen" required
                                           value="{{ $oldData->namafile }}" />
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="nama_kategori" name="id_kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id_kategori }}"
                                            {{ old('nama_kategori', $oldData->id_kategori) == $item->id_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tahun -->
                            <div class="mb-3">
                                <label for="periode_tahunakademik" class="form-label">Periode/Tahun Akademik</label>
                                <input type="text" class="form-control" id="periode_tahunakademik" name="periode_tahunakademik"
                                    placeholder="isi periode atau tahun akademik" value="{{ $oldData->periode_tahunakademik }}" required />
                            </div>

                            <!-- Pilih Dokumen -->
                            <div class="mb-3"> 
                                <label for="formFileMultiple" class="form-label">Pilih Dokumen</label>
                                <input class="form-control" type="file" name="file" id="formFileMultiple" multiple />
                                <p class="form-text">Unggah ulang dokumen jika ingin mengubah file yang sudah ada.</p>
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>

                            <!-- Kirim -->
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='/Pelaksanaan/Fakultas'">Batal</button>                            
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
