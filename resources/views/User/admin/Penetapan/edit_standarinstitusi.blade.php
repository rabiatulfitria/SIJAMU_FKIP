@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center" style="margin-left: 15px;">Ubah Data Dokumen Standar SPMI Universitas Tronojoyo Madura</div>
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
                        <form method="POST" action="{{ route('updateDokumenStandar', $oldData->id_standarinstitut) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Nama Dokumen -->
                            <div class="mb-3">
                                <label class="form-label" for="bx bx-file">Nama Dokumen</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="bx bx-file"></i></span>
                                    <input type="text" class="form-control" id="bx bx-file" name="namafile"
                                        placeholder="Nama Dokumen" required
                                        value="{{ old('namafile', $oldData->namafile) }}" />
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Standar Pendidikan Universitas Trunojoyo Madura"
                                        {{ $oldData->kategori == 'Standar Pendidikan Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Pendidikan Universitas Trunojoyo Madura</option>
                                    <option value="Standar Penelitian Universitas Trunojoyo Madura"
                                        {{ $oldData->kategori == 'Standar Penelitian Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Penelitian Universitas Trunojoyo Madura</option>
                                    <option value="Standar Pengabdian Kepada Masyarakat Universitas Trunojoyo Madura"
                                        {{ $oldData->kategori == 'Standar Pengabdian Kepada Masyarakat Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Pengabdian Kepada Masyarakat Universitas Trunojoyo Madura</option>
                                    <option value="Standar Layanan Kemahasiswaan Universitas Trunojoyo Madura"
                                        {{ $oldData->kategori == 'Standar Layanan Kemahasiswaan Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Layanan Kemahasiswaan Universitas Trunojoyo Madura</option>
                                    <option value="Standar Layanan Kerjasama Universitas Trunojoyo Madura"
                                        {{ $oldData->kategori == 'Standar Layanan Kerjasama Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Layanan Kerjasama Universitas Trunojoyo Madura</option>
                                    <option value="Standar Tata Kelola Universitas Trunojoyo Madura"
                                        {{ $oldData->kategori == 'Standar Tata Kelola Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Tata Kelola Universitas Trunojoyo Madura</option>
                                </select>
                            </div>

                            <!-- Tahun -->
                            <div class="mb-3">
                                <label for="tanggal_ditetapkan" class="form-label">Tanggal Ditetapkan</label>
                                <input type="date" class="form-control" id="tanggal_ditetapkan" name="tanggal_ditetapkan"
                                    placeholder="Tanggal Ditetapkan" required
                                    value="{{ old('tanggal_ditetapkan', $oldData->penetapan->tanggal_ditetapkan) }}" />
                            </div>

                            <!-- Nama Program Studi -->
                            <div class="mb-3">
                                <label class="form-label" for="namaprodi">Program Studi</label>
                                <select class="form-select" id="namaprodi" name="id_prodi" required>
                                    <option value="" disabled>Pilih Program Studi</option>
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->id_prodi }}"
                                            {{ old('id_prodi', $oldData->id_prodi) == $item->id_prodi ? 'selected' : '' }}>
                                            {{ $item->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pilih Dokumen -->
                            <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">Pilih Dokumen</label>
                                <input class="form-control" type="file" name="file" id="formFileMultiple" multiple />
                                <p class="form-text text-muted">Unggah ulang dokumen jika ingin mengubah file yang sudah
                                    ada.</p>
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='/Penetapan/StandarInstitusi'">Batal</button>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection
