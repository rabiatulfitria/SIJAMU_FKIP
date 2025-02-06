@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center">Unggah Dokumen Peningkatan</div>
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
                        <form method="POST" action="{{ url('tambahDokumenPeningkatan-2') }}" enctype="multipart/form-data">
                            <div class="mb-3">
                                @csrf
                                <label class="form-label" for="bx bx-file">Nama Dokumen Peningkatan</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="bx bx-file"></i></span>
                                    <input type="text" class="form-control" id="bx bx-file" name="nama_dokumen"
                                        placeholder="Nama Dokumen" required />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="bidang_standar">Nama Bidang Pengaturan Standar</label>
                                <select class="form-select" id="bidang_standar" name="bidang_standar" required>
                                    <option value="" disabled selected>Pilih Bidang Standar</option>
                                    <option value="Standar Pendidikan Universitas Trunojoyo Madura">Standar Pendidikan Universitas Trunojoyo Madura</option>
                                    <option value="Standar Penelitian Universitas Trunojoyo Madura">Standar Penelitian Universitas Trunojoyo Madura</option>
                                    <option value="Standar Pengabdian Kepada Masyarakat Universitas Trunojoyo Madura">Standar Pengabdian Kepada Masyarakat Universitas Trunojoyo Madura</option>
                                    <option value="Standar Layanan Kemahasiswaan Universitas Trunojoyo Madura">Standar Layanan Kemahasiswaan Universitas Trunojoyo Madura</option>
                                    <option value="Standar Layanan Kerjasama Universitas Trunojoyo Madura">Standar Layanan Kerjasama Universitas Trunojoyo Madura</option>
                                    <option value="Standar Tata Kelola Universitas Trunojoyo Madura">Standar Tata Kelola Universitas Trunojoyo Madura</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="nama_prodi">Nama Program Studi</label>
                                <select class="form-select" id="id_prodi" name="id_prodi" required>
                                    <option value="" disabled
                                        {{ old('id_prodi', $id_prodi ?? null) === null ? 'selected' : '' }}>
                                        Pilih Program Studi
                                    </option>
                                    @foreach ($prodi as $opsi)
                                        <option value="{{ $opsi->id_prodi }}"
                                            {{ old('id_prodi', $id_prodi ?? null) == $opsi->id_prodi ? 'selected' : '' }}>
                                            {{ $opsi->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tanggal Penetapan Baru</label>
                                <input type="text" class="form-control" id="tanggal_penetapan_baru"
                                    name="tanggal_penetapan_baru" placeholder="mm/dd/yyyy" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="formFileMultiple">Unggah Dokumen</label>
                                <input type="file" class="form-control" id="formFileMultiple" multiple
                                    name="file_peningkatan" />
                                <p class="form-text" style="color: #7ebcfe">Maksimum 5120 KB (5 MB)</p>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
