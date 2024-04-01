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
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>Form tambah kecamatan </h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('save_kec') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="kode">Kode</label>
                                <input type="text" class="form-control @error('kode') is-invalid @enderror" name="kode" id="kode" placeholder="kode" value="{{ old('kode') }}">
                            </div>
                            <div class="col-lg-12  mb-3">
                                <label for="">Kecamatan</label>
                                <input type="text" class="form-control" name="nama_kecamatan" id="nama_kecamatan" placeholder="Jumlah maksimal penerima" value="{{ old('nama_kecamatan') }}">
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-md btn-primary">Simpan</button>
                                <a href="{{ url('/wilayah') }}" class="btn btn-md btn-danger">Batal</a>    
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
@endsection