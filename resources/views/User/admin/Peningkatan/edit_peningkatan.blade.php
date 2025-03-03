@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center">Edit Dokumen Peningkatan</div>
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
                        <form method="POST" action="{{ route('updateDokumenPeningkatan', $oldData->id_peningkatan) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="bx bx-file">Nama Dokumen</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="bx bx-file"></i></span>
                                    <input type="text" class="form-control" id="bx bx-file" name="nama_dokumen"
                                        placeholder="Nama Dokumen" required value="{{ $oldData->nama_dokumen }}" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="bidang_standar">Nama Bidang Pengaturan Standar</label>
                                <select class="form-select" id="bidang_standar" name="bidang_standar" required>
                                    <option value="" disabled selected>Pilih Bidang Standar</option>
                                    <option value="Standar Pendidikan Universitas Trunojoyo Madura"
                                        {{ $oldData->bidang_standar == 'Standar Pendidikan Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Pendidikan Universitas Trunojoyo Madura</option>
                                    <option value="Standar Penelitian Universitas Trunojoyo Madura"
                                        {{ $oldData->bidang_standar == 'Standar Penelitian Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Penelitian Universitas Trunojoyo Madura</option>
                                    <option value="Standar Pengabdian Kepada Masyarakat Universitas Trunojoyo Madura"
                                        {{ $oldData->bidang_standar == 'Standar Pengabdian Kepada Masyarakat Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Pengabdian Kepada Masyarakat Universitas Trunojoyo Madura</option>
                                    <option value="Standar Layanan Kemahasiswaan Universitas Trunojoyo Madura"
                                        {{ $oldData->bidang_standar == 'Standar Layanan Kemahasiswaan Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Layanan Kemahasiswaan Universitas Trunojoyo Madura</option>
                                    <option value="Standar Layanan Kerjasama Universitas Trunojoyo Madura"
                                        {{ $oldData->bidang_standar == 'Standar Layanan Kerjasama Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Layanan Kerjasama Universitas Trunojoyo Madura</option>
                                    <option value="Standar Tata Kelola Universitas Trunojoyo Madura"
                                        {{ $oldData->bidang_standar == 'Standar Tata Kelola Universitas Trunojoyo Madura' ? 'selected' : '' }}>
                                        Standar Tata Kelola Universitas Trunojoyo Madura</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="nama_prodi" class="form-label">Nama Program Studi</label>
                                <select class="form-select" id="nama_prodi" name="id_prodi" required>
                                    <option value="" disabled>Pilih Program Studi</option>
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->id_prodi }}"
                                            {{ old('id_prodi', $id_prodi ?? null) == $item->id_prodi ? 'selected' : '' }}>
                                            {{ $item->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_penetapan_baru" class="form-label">Tanggal Penetapan Baru</label>
                                <input type="date" class="form-control" id="tanggal_penetapan_baru"
                                    name="tanggal_penetapan_baru" placeholder="mm/dd/yyyy" required
                                    value="{{ $oldData->tanggal_penetapan_baru }}" />
                            </div>

                            <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">Pilih Dokumen</label>
                                <input class="form-control" type="file" name="file_peningkatan" id="formFileMultiple"
                                    multiple />
                                <p class="form-text text-muted">Unggah ulang dokumen jika ingin mengubah file yang sudah
                                    ada.</p>
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>

                            <button type="submit" class="btn btn-primary">Ubah</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
@endsection

<script>
    function toggleManualInput() {
        var namaDokumenSelect = document.getElementById("nama_fileeval");
        var manualInputDiv = document.getElementById("manualNamaDokumen");

        if (namaDokumenSelect.value === "Dokumen Lainnya") {
            manualInputDiv.style.display = "block";
            document.getElementById("manual_namaDokumen").required = true;
        } else {
            manualInputDiv.style.display = "none";
            document.getElementById("manual_namaDokumen").required = false;
        }
    }
</script>
