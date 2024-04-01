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

    <div class="card">
        <div class="card-header">
            <h5>Form tambah pengguna </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('usr.index') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="id" placeholder="Masukan nama pengguna" value="{{ old('name') }}">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" placeholder="Buat username baru" value="{{ old('username') }}">
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label for="">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Masukan password">
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label for="">Role pengguna</label>
                        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                            <option selected disabled>Pilih hak akses</option>
                            <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Dinas</option>
                            <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Kelurahan</option>
                        </select>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Desa/kelurahan</label>
                        <select name="desa_kelurahan_id" id="desa_kelurahan_id" class="form-control" disabled>
                            <option selected disabled>Pilih desa/kelurahan</option>
                            @foreach ($desa_kelurahan as $item)
                                <option value="{{ $item->id }}">{{ $item->kecamatan->nama_kecamatan }} - {{ $item->nama_desakelurahan }}</option>
                            @endforeach    
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-md btn-primary">Simpan</button>
                        <a href="{{ route('usr.index') }}" class="btn btn-md btn-danger">Batal</a>    
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
@endsection
@push('js')
    <script>
        $(document).ready(function(e){
            let role = $('#role').val();
            if (role == 2) {
                $('#desa_kelurahan_id').attr('disabled', false);
            }
        });
        $(document).on('change', '#role', function(e){
            e.preventDefault();
            let nilai = $(this).val();
            if (nilai == 1) {
                $('#desa_kelurahan_id').attr('disabled', true);
            } else {
                $('#desa_kelurahan_id').attr('disabled', false);
            }
        })
    </script>
@endpush