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
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Data user</h4>
                        Nama : {{ auth()->user()->name }} <br />
                        Username : {{ auth()->user()->username }} <br />
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Form ganti password</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('change_user') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ auth()->user()->name }}">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Usernama</label>
                                <input type="text" class="form-control" name="username" id="username" value="{{ auth()->user()->username }}">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Kosongkan jika tidak ingin merubah">
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-md btn-primary">Simpan</button>
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