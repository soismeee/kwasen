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
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>Penghasilan orang tua</h4>
                    <hr />
                    <h2 class="text-center">{{ number_format($kriteria->penghasilan_ortu,0,',','.') }}</h2>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_po">Ubah data</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>Usia</h4>
                    <hr />
                    <h2 class="text-center">{{ $kriteria->usia }} Tahun</h2>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_usia">Ubah data</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>Alamat</h4>
                    <hr />
                    <h2 class="text-center">{{ $kriteria->alamat }}</h2>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_alamat">Ubah data</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>Status</h4>
                    <hr />
                    <h2 class="text-center">{{ $kriteria->status }}</h2>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_status">Ubah data</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>KIA</h4>
                    <hr />
                    <h2 class="text-center">{{ $kriteria->kia }}</h2>
                    <hr />
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary" id="k_kia">Ubah data</button>
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
        $(document).on('click', '#k_po', function(e){
            e.preventDefault();
            $('.form').html(`<input type="text" name="penghasilan_ortu" id="penghasilan_ortu" class="form-control" value="{{ $kriteria->penghasilan_ortu }}">`);
            $('#simpan_perubahan').val('penghasilan_ortu');
            $('#formModal').modal('show');
        });

        $(document).on('click', '#k_usia', function(e){
            e.preventDefault();
            $('.form').html(`<input type="text" name="usia" id="usia" class="form-control" value="{{ $kriteria->usia }}">`);
            $('#simpan_perubahan').val('usia');
            $('#formModal').modal('show');
        });

        $(document).on('click', '#k_alamat', function(e){
            e.preventDefault();
            $('.form').html(`<input type="text" name="alamat" id="alamat" class="form-control" value="{{ $kriteria->alamat }}">`);
            $('#simpan_perubahan').val('alamat');
            $('#formModal').modal('show');
        });

        $(document).on('click', '#k_status', function(e){
            e.preventDefault();
            $('.form').html(`<input type="text" name="status" id="status" class="form-control" value="{{ $kriteria->status }}">`);
            $('#simpan_perubahan').val('status');
            $('#formModal').modal('show');
        });

        $(document).on('click', '#k_kia', function(e){
            e.preventDefault();
            $('.form').html(`<input type="text" name="kia" id="kia" class="form-control" value="{{ $kriteria->kia }}">`);
            $('#simpan_perubahan').val('kia');
            $('#formModal').modal('show');
        });
    </script>
@endpush