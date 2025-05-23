@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center" style="margin-left: 15px;">Ubah Data Dokumen Perangkat SPMI</div>
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
                        <form method="POST" action="{{ route('updateDokumenPerangkat', $oldData->id_dokspmi) }}"
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
                                    <option value="Kebijakan" {{ $oldData->kategori == 'Kebijakan' ? 'selected' : '' }}>
                                        Kebijakan</option>
                                    <option value="Manual" {{ $oldData->kategori == 'Manual' ? 'selected' : '' }}>Manual
                                    </option>
                                    <option value="Standar" {{ $oldData->kategori == 'Standar' ? 'selected' : '' }}>Standar
                                    </option>
                                    <option value="Formulir" {{ $oldData->kategori == 'Formulir' ? 'selected' : '' }}>
                                        Formulir</option>
                                </select>
                            </div>

                            <!-- Tahun -->
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tanggal Ditetapkan</label>
                                <input type="date" class="form-control" id="tanggal_ditetapkan" name="tanggal_ditetapkan"
                                    placeholder="Tanggal Ditetapkan" required
                                    value="{{ old('tanggal_ditetapkan', $oldData->penetapan->tanggal_ditetapkan) }}" />
                            </div>

                            <!-- Nama Program Studi -->
                            {{-- <div class="mb-3">
                                <label for="nama_prodi" class="form-label">Nama Program Studi</label>
                                <select class="form-select" id="nama_prodi" name="nama_prodi" required>
                                    <option value="">Pilih Program Studi</option>
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->id_prodi }}" {{ $oldData->namaprodi == $item->id_prodi ? 'selected' : '' }}>
                                            {{ $item->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <!-- Pilih Dokumen -->
                            <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">Pilih Dokumen</label>
                                <input class="form-control" type="file" name="file" id="formFileMultiple" multiple />
                                @if ($oldData->file)
                                    <p>File sebelumnya: <a
                                            href="{{ route('dokumenperangkat.tampil', ['id_dokspmi' => $oldData->id_dokspmi, 'namafile' => $oldData->namafile]) }}"
                                            target="_blank">Buka File</a></p>
                                @endif
                                <p class="form-text" style="color: #7ebcfe">Maksimum (20 MB)</p>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='/Penetapan/DokumenSPMI'">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
