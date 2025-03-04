@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center">Edit Dokumen Audit Mutu Internal (AMI)</div>
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
                        <form method="POST" action="{{ route('updateDokumenEvaluasi', $oldData->id_evaluasi) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label class="form-label" for="">Nama Dokumen</label>
                            <select class="form-select" id="nama_fileeval" name="nama_fileeval" required
                                onchange="toggleManualInput()">
                                <option value="" disabled>Pilih Nama Dokumen</option>
                                <option value="Isian Laporan AMI"
                                    {{ isset($namaFileEval) && $namaFileEval === 'Isian Laporan AMI' ? 'selected' : '' }}>
                                    Isian Laporan AMI
                                </option>
                                <option value="Berkas Audit (AMI)"
                                    {{ isset($namaFileEval) && $namaFileEval === 'Berkas Audit (AMI)' ? 'selected' : '' }}>
                                    Berkas Audit (AMI)
                                </option>
                                <option value="Dokumen Lainnya"
                                    {{ isset($namaFileEval) && $namaFileEval === 'Dokumen Lainnya' ? 'selected' : '' }}>
                                    Dokumen Lainnya
                                </option>
                            </select>

                            <div class="mb-3" id="manualNamaDokumen"
                                style="display: {{ isset($namaFileEval->nama_fileeval) && $namaFileEval->nama_fileeval == 'Dokumen Lainnya' ? 'block' : 'none' }}; padding-top:8px">
                                <label class="form-label" for="manual_namaDokumen">Ketikan Nama Dokumen</label>
                                <input type="text" class="form-control" id="manual_namaDokumen" name="manual_namaDokumen"
                                    placeholder="Nama Dokumen Lainnya" />
                            </div>

                            <div class="divider text-start">
                                <div class="divider-text">Keterangan</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="">Program Studi</label>
                                <select class="form-select" id="namaprodi" name="id_prodi" required>
                                    <option value="" disabled>Pilih Program Studi</option>
                                    <!-- ambil data sebelumnya di tabel nama_file_eval yang terdapat kolom id_evaluasi dan id_prodi -->
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->id_prodi }}"
                                            {{ old('id_prodi', $id_prodi ?? null) == $item->id_prodi ? 'selected' : '' }}>
                                            {{ $item->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="">Tanggal Terakhir Dilakukan</label>
                                <input type="date" class="form-control" id="tanggal_terakhir_dilakukan"
                                    name="tanggal_terakhir_dilakukan"
                                    value="{{ old('tanggal_terakhir_dilakukan', $oldData->tanggal_terakhir_dilakukan) }}" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="">Tanggal Diperbarui</label>
                                <input type="date" class="form-control" id="tanggal_diperbarui" name="tanggal_diperbarui"
                                    value="{{ old('tanggal_diperbarui', $oldData->tanggal_diperbarui) }}" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="formFileMultiple">Pilih Dokumen</label>
                                <input type="file" class="form-control" value="" id="formFileMultiple" multiple
                                    name="file" />
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Ubah</button>
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
