@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center">Edit Dokumen Pelaksanaan Fakultas</div>
        </div>
    @endsection

    @section('content')
        <div class="row">

            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updatePelaksanaanFakultas' . $pelaksanaan->id_plks_fklts) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Nama Dokumen -->
                            <div class="mb-3">
                                <label class="form-label" for="bx bx-file">Nama Dokumen</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-file"></i></span>
                                    <input type="text" class="form-control" id="bx bx-file" name="namafile" placeholder="Nama Dokumen" required
                                           value="{{ $pelaksanaan->namafile }}" />
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="nama_kategori" name="nama_kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Renstra Fakultas" {{ $pelaksanaan->kategori == 'Renstra Fakultas' ? 'selected' : '' }}>Renstra Fakultas</option>
                                    <option value="Laporan Kinerja Fakultas" {{ $pelaksanaan->kategori == 'Laporan Kinerja Fakultas' ? 'selected' : '' }}>Laporan Kinerja Fakultas</option>
                                </select>
                            </div>

                            <!-- Tahun -->
                            <!-- <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Tahun" required min="1900" max="2099"
                                       {{-- value="{{ $pelaksanaan->tahun }}" /> --}}
                            </div> -->

                            <!-- Pilih Dokumen -->
                            <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">Pilih Dokumen</label>
                                <input class="form-control" type="file" name="files[]" id="formFileMultiple" multiple />
                                <p class="form-text" style="color: #7ebcfe">Unggah ulang dokumen jika ingin mengubah file yang sudah ada.</p>
                                <p class="form-text" style="color: #7ebcfe">Maksimum 5120 KB (5 MB)</p>
                            </div>

                            <!-- Kirim -->
                            <button type="submit" class="btn btn-primary">Ubah</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
