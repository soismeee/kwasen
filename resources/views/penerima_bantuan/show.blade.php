@extends('layout.main')
@section('container')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Detail Penduduk</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                            Nama
                        </div>
                        <div class="col-lg-10">
                            : {{ $data->penduduk->nama }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            NIK
                        </div>
                        <div class="col-lg-10">
                            : {{ $data->penduduk->nik }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            Tanggal Lahir
                        </div>
                        <div class="col-lg-10">
                            : {{ $data->penduduk->tanggal_lahir }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            Jenis Kelamin
                        </div>
                        <div class="col-lg-10">
                            : {{ $data->penduduk->jekel }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            Alamat
                        </div>
                        <div class="col-lg-10">
                            : {{ $data->penduduk->alamat_lengkap }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4>Data kriteria penduduk</h4>
                    <table class="table table-bordered">
                        <tr>
                            <td width="20%">Penghasilan Ortu</td>
                            <td width="80%">: Rp. {{ number_format($data->penghasilan_ortu,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <td width="20%">Status</td>
                            <td width="80%">: {{ $data->status }}</td>
                        </tr>
                        <tr>
                            <td width="20%">KIA</td>
                            <td width="80%">: {{ $data->kia }}</td>
                        </tr>
                        <tr>
                            <td width="20%">Alamat</td>
                            <td width="80%">: {{ $data->alamat }}</td>
                        </tr>
                        <tr>
                            <td width="20%"><strong>Status</strong></td>
                            <td width="80%">: <strong>{{ $hasil }}</strong></td>
                        </tr>
                    </table>
                    <a href="{{ url('cek') }}" class="btn btn-md btn-primary">Kembali</a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection