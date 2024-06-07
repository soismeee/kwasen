@extends('layout.main')
@push('css')
        <!-- Custom styles for this page -->
        <link href="/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('container')
    <!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>Penghasilan</h4>
                    <hr />
                    <h5 class="text-center">{{ number_format($kriteria->penghasilan,0,',','.') }}</h5>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_penghasilan">Ubah data</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>Status</h4>
                    <hr />
                    <h5 class="text-center">{{ $kriteria->status }}</h5>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_status">Ubah data</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>Polri/ASN</h4>
                    <hr />
                    <h5 class="text-center">{{ $kriteria->polri_asn }}</h5>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_polri_asn">Ubah data</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>Penerima Bansos Lain</h4>
                    <hr />
                    <h5 class="text-center">{{ $kriteria->pbl }}</h5>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_pbl">Ubah data</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>DTKS</h4>
                    <hr />
                    <h5 class="text-center">{{ $kriteria->dtks }}</h5>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_dtks">Ubah data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('kriteria.modal')
<!-- /.container-fluid -->
@endsection

@push('js')
    <script>
        $(document).on('click', '#k_penghasilan', function(e){
            e.preventDefault();
            $('.form').html(`<input type="text" name="penghasilan" id="penghasilan" class="form-control" value="{{ $kriteria->penghasilan }}">`);
            $('#simpan_perubahan').val('penghasilan');
            $('#formModal').modal('show');
        });

        $(document).on('click', '#k_status', function(e){
            e.preventDefault();
            $('.form').html(`
            <select name="status" id="status" class="form-control">
                <option value="Disabilitas" {{ $kriteria->status == "Disabilitas" ? 'selected' : '' }}>Disabilitas</option>
                <option value="Lansia" {{ $kriteria->status == "Lansia" ? 'selected' : '' }}>Lansia</option>
                <option value="Ibu Hamil" {{ $kriteria->status == "Ibu Hamil" ? 'selected' : '' }}>Ibu Hamil</option>
            </select>
            `);
            $('#simpan_perubahan').val('status');
            $('#formModal').modal('show');
        });

        $(document).on('click', '#k_polri_asn', function(e){
            e.preventDefault();
            $('.form').html(`
            <select name="polri_asn" id="polri_asn" class="form-control">
                <option value="Ya" {{ $kriteria->polri_asn == "Ya" ? 'selected' : '' }}>Ya</option>
                <option value="Tidak" {{ $kriteria->polri_asn == "Tidak" ? 'selected' : '' }}>Tidak</option>
            </select>
            `);
            $('#simpan_perubahan').val('polri_asn');
            $('#formModal').modal('show');
        });

        $(document).on('click', '#k_pbl', function(e){
            e.preventDefault();
            $('.form').html(`
            <select name="pbl" id="pbl" class="form-control">
                <option value="Penerima" {{ $kriteria->pbl == "Penerima" ? 'selected' : '' }}>Penerima</option>
                <option value="Bukan" {{ $kriteria->pbl == "Bukan" ? 'selected' : '' }}>Bukan</option>
            </select>
            `);
            $('#simpan_perubahan').val('pbl');
            $('#formModal').modal('show');
        });

        $(document).on('click', '#k_dtks', function(e){
            e.preventDefault();
            $('.form').html(`
            <select name="dtks" id="dtks" class="form-control">
                <option value="Belum" {{ $kriteria->dtks == "Belum" ? 'selected' : '' }}>Belum</option>
                <option value="Sudah" {{ $kriteria->dtks == "Sudah" ? 'selected' : '' }}>Sudah</option>
            </select>
            `);
            $('#simpan_perubahan').val('dtks');
            $('#formModal').modal('show');
        });
    </script>
@endpush