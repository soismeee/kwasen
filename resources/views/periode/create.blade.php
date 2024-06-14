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
                    <h5>Form tambah periode </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('periode.index') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="periode">Periode</label>
                                <input type="text" class="form-control @error('periode') is-invalid @enderror" name="periode" id="periode" placeholder="Periode" value="{{ old('periode') }}">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="">Tanggal mulai</label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" name="tanggal_mulai" id="tanggal_mulai" min="{{ date('Y-m-d', strtotime($batas_tanggal)) }}" value="{{ old('tanggal_mulai') }}">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="">Tanggal akhir</label>
                                <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" name="tanggal_akhir" id="tanggal_akhir" min="{{ date('Y-m-d', strtotime($batas_tanggal)) }}" value="{{ old('tanggal_akhir') }}">
                            </div>
                            <div class="col-lg-12  mb-3">
                                <label for="">Maksimal Penerimaan</label>
                                <input type="number" class="form-control" name="maksimal_penerima" id="maksimal_penerima" placeholder="Jumlah maksimal penerima" value="{{ old('maksimal_penerima') }}">
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-md btn-primary">Simpan</button>
                                <a href="{{ route('periode.index') }}" class="btn btn-md btn-danger">Batal</a>    
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