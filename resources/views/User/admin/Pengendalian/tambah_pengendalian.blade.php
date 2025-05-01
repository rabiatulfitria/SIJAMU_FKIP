@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center" style="margin-left: 15px;">Tambah Dokumen Pengendalian</div>
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
                        <form method="POST" action="{{ url('tambahDokumenPengendalian-2') }}"
                            enctype="multipart/form-data">
                            <!--<div class="mb-3">-->
                            <!--    @csrf-->
                            <!--    <label class="form-label" for="">Nama Bidang Pengaturan Standar</label>-->
                            <!--    <select class="form-select" id="bidang_standar" name="bidang_standar" required-->
                            <!--        onchange="toggleManualInput()">-->
                            <!--        <option value="" disabled selected>Pilih Nama Bidang Standar</option>-->
                            <!--        <option value="Standar Pendidikan">Standar Pendidikan Universitas Trunojoyo Madura-->
                            <!--        </option>-->
                            <!--        <option value="Standar Penelitian">Standar Penelitian Universitas Trunojoyo Madura-->
                            <!--        </option>-->
                            <!--        <option value="Standar Pengabdian">Standar Pengabdian Kepada Masyarakat Universitas-->
                            <!--            Trunojoyo Madura</option>-->
                            <!--        <option value="Standar ">Standar Penelitian Universitas Trunojoyo Madura</option>-->
                            <!--        <option value="Standar Lainnya">Standar Lainnya</option>-->
                            <!--    </select>-->
                            <!--    <div class="mb-3" id="manualNamaDokumen" style="display: none; padding-top:8px">-->
                            <!--        <label class="form-label" for="manual_namaDokumen">Ketikan Nama Standar</label>-->
                            <!--        <input type="text" class="form-control" id="manual_namaBidangStandar" name="manual_namaBidangStandar"-->
                            <!--            placeholder="Nama Standar Lainnya" />-->
                            <!--    </div>-->
                            <!--</div>-->

                            <div class="mb-3">
                                @csrf
                                <label class="form-label" for="bx bx-file">Nama Dokumen</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="bx bx-file"></i></span>
                                    <input type="text" class="form-control" id="bx bx-file" name="nama_dokumen"
                                        placeholder="Nama Dokumen" required />
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Tahun" required min="1900" max="2099" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="id_prodi">Nama Program Studi</label>
                                <select class="form-select" id="id_prodi" name="id_prodi" required>
                                    <option value="" disabled selected>Pilih Program Studi</option>
                                    @foreach($prodi as $opsi)
                                        <option value="{{ $opsi->id_prodi }}">{{ $opsi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="formFileMultiple">Dokumen RTM</label>
                                <input type="file" class="form-control" id="formFileMultiple" multiple
                                    name="file_rtm" />
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="formFileMultiple">Dokumen RTL</label>
                                <input type="file" class="form-control" id="formFileMultiple" multiple
                                    name="file_rtl" />
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='/Pengendalian/Standar/RTM'">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
