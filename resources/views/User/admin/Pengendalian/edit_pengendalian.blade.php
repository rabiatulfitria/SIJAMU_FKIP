@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center" style="margin-left: 15px;">Ubah Data Pengendalian Standar SPMI Perguruan Tinggi</div>
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
                        <form method="POST" action="{{ route('updateDokumenPengendalian', $oldData->id_pengendalian) }}"
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
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Tahun"
                                    required value="{{ $oldData->tahun }}" />
                            </div>

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

                            <div class="mb-3">
                                <label class="form-label" for="formFileMultiple">Laporan RTM</label>
                                <input type="file" class="form-control" id="formFileMultiple" multiple="" name="file_rtm" id="file_rtm">
                                @if ($oldData->file_rtm)
                                    <p>File sebelumnya: <a
                                            href="{{ route('dokumenpengendalian.tampil', ['id_pengendalian' => $oldData->id_pengendalian, 'jenis_file' => 'rtm', 'file_rtm' => basename($oldData->file_rtm)]) }}"
                                            target="_blank">Buka File RTM</a></p>
                                @endif
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="formFileMultiple">Laporan RTL</label>
                                <input type="file" class="form-control" id="formFileMultiple" multiple="" name="file_rtl" id="file_rtl">
                                @if ($oldData->file_rtl)
                                    <p>File sebelumnya: <a
                                            href="{{ route('dokumenpengendalian.tampil', ['id_pengendalian' => $oldData->id_pengendalian, 'jenis_file' => 'rtl', 'file_rtl' => basename($oldData->file_rtl)]) }}"
                                            target="_blank">Buka File RTL</a></p>
                                @endif
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='/Pengendalian/Standar/RTM'">Batal</button>
                            </div>
                        </form>
                    </div>
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
