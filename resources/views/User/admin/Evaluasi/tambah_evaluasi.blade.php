@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center" style="margin-left: 15px;">Tambah Dokumen Audit Mutu Internal (AMI)</div>
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
                        <form method="POST" action="{{ url('tambahDokumenEvaluasi-2') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="bx bx-file">Nama Dokumen</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="bx bx-file"></i></span>
                                    <input type="text" class="form-control" id="bx bx-file" name="nama_dokumen"
                                        placeholder="Nama Dokumen" required />
                                </div>
                            </div>

                            {{-- <select class="form-select" id="inputmanual" name="nama_dokumen" required
                                onchange="toggleManualInput()">
                                <option value="" disabled selected>Pilih Nama Dokumen</option>
                                <option value="Laporan Isian AMI">Isian Laporan AMI</option>
                                <option value="Berkas Audit (AMI)">Berkas Audit (AMI)</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <div class="mb-3" id="manualNamaDokumen" style="display: none; padding-top:8px">
                                <label class="form-label" for="manual_namaDokumen">Ketikan Nama Dokumen</label>
                                <input type="text" class="form-control" id="manual_namaDokumen" name="manual_namaDokumen"
                                    placeholder="Nama Dokumen Lainnya" />
                            </div> --}}
                            {{-- <div class="divider text-start">
                                <div class="divider-text">Keterangan</div>
                            </div> --}}

                            <div class="mb-3">
                                <label class="form-label" for="nama_prodi">Nama Program Studi</label>
                                <select class="form-select" id="nama_prodi" name="id_prodi" required>
                                    <option value="">Pilih Program Studi</option>
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->id_prodi }}">{{ $item->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="">Tanggal Terakhir Dilakukan</label>
                                <input type="date" class="form-control" id="tanggal_terakhir_dilakukan"
                                    name="tanggal_terakhir_dilakukan" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="">Tanggal Diperbarui</label>
                                <input type="date" class="form-control" id="tanggal_diperbarui"
                                    name="tanggal_diperbarui" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="formFileMultiple">Pilih Dokumen</label>
                                <input type="file" class="form-control" id="formFileMultiple" multiple
                                    name="file_eval" />
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

<script>
    function toggleManualInput() {
        var namaDokumenSelect = document.getElementById("inputmanual");
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
