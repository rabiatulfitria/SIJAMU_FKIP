@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center" style="margin-left: 15px;">Ubah Data Dokumen Audit Mutu Internal (AMI)</div>
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
                        <form method="POST" action="{{ route('updateDokumenEvaluasi', $oldData->id_dokumeneval) }}"
                            enctype="multipart/form-data">
                            {{-- @dump($oldData) --}}

                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="nama_dokumen">Nama Dokumen</label>
                                <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen"
                                    value="{{ old('nama_dokumen', $oldData->nama_dokumen) }}" required placeholder="Masukkan Nama Dokumen">
                            </div>
                            
                            {{-- <select class="form-select" id="nama_dokumen" name="nama_dokumen" required
                                onchange="toggleManualInput()">
                                <option value="" disabled>Pilih Nama Dokumen</option>
                                <option value="Isian Laporan AMI"
                                    {{ $oldData->nama_dokumen === 'Isian Laporan AMI' ? 'selected' : '' }}>
                                    Isian Laporan AMI
                                </option>
                                <option value="Berkas Audit (AMI)"
                                    {{ $oldData->nama_dokumen === 'Berkas Audit (AMI)' ? 'selected' : '' }}>
                                    Berkas Audit (AMI)
                                </option>
                                <option value="Lainnya"
                                    {{ !in_array($oldData->nama_dokumen, ['Isian Laporan AMI', 'Berkas Audit (AMI)']) ? 'selected' : '' }}>
                                    Lainnya
                                </option>
                            </select>
                            <div class="mb-3" id="manualNamaDokumen"
                                style="display: {{ !in_array($oldData->nama_dokumen, ['Isian Laporan AMI', 'Berkas Audit (AMI)']) ? 'block' : 'none' }}; padding-top:8px">
                                <label class="form-label" for="manual_namaDokumen">Ketik Nama Dokumen</label>
                                <input type="text" class="form-control" id="manual_namaDokumen" name="manual_namaDokumen"
                                    value="{{ !in_array($oldData->nama_dokumen, ['Isian Laporan AMI', 'Berkas Audit (AMI)']) ? $oldData->nama_dokumen : '' }}"
                                    placeholder="Nama Dokumen Lainnya" />
                            </div> --}}
                            {{-- <div class="divider text-start">
                                <div class="divider-text">Keterangan</div>
                            </div> --}}

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
                                <label class="form-label" for="">Tanggal Terakhir Dilakukan</label>
                                <input type="date" class="form-control" id="tanggal_terakhir_dilakukan"
                                    name="tanggal_terakhir_dilakukan"
                                    value="{{ old('tanggal_terakhir_dilakukan', $oldData->evaluasi->tanggal_terakhir_dilakukan) }}" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="">Tanggal Diperbarui</label>
                                <input type="date" class="form-control" id="tanggal_diperbarui" name="tanggal_diperbarui"
                                    value="{{ old('tanggal_diperbarui', $oldData->evaluasi->tanggal_diperbarui) }}" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="formFileMultiple">Pilih Dokumen</label>
                                <input type="file" class="form-control" value="" id="formFileMultiple" multiple
                                    name="file_eval" />
                                @if ($oldData->file)
                                    <p>File sebelumnya:
                                        <a href="{{ route('dokumenevaluasi.tampil', [
                                            'id_dokumeneval' => $oldData->id_dokumeneval,
                                            'nama_dokumen' => $oldData->nama_dokumen,
                                        ]) }}"
                                            target="_blank">Buka File</a>
                                    </p>
                                @endif
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='/Evaluasi/AuditMutuInternal'">Batal</button>
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
            document.getElementById("manual_namaDokumen").value = ''; // Kosongkan input jika bukan "Dokumen Lainnya
        }
    }
</script>
