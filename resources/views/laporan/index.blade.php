@extends('layout.main')
@section('container')
    <!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Data Penerima Bantuan</h5>
                <a href="#" class="btn btn-md btn-primary" id="cetak" target="_blank">Cetak Laporan</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6 mb-3">
                    <label>Filter Desa/Kelurahan</label>
                    <select class="form-control" name="desa_kelurahan" id="desa_kelurahan">
                        <option selected disabled>Pilih desa/kelurahan</option>
                        @foreach ($dskl as $item)
                            <option value="{{ $item['desa_kelurahan'] }}">{{ $item['desa_kelurahan'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Filter Diterima/tidak</label>
                    <select class="form-control" name="status" id="status">
                        <option value="">Pilih status</option>
                        <option value="diterima">Diterima</option>
                        <option value="tidak">Tidak diterima</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-pengguna" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th width="20%">Nama</th>
                            <th width="20%">NIK</th>
                            <th width="10%">Tgl lahir</th>
                            <th width="25%">Alamat</th>
                            <th width="10%">Jenis Kelamin</th>
                            <th width="10%">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="text-center" id="loading">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection

@push('js')
<script>
    $(document).ready(function(e){
        loaddata();
        link_cetak();
    });

    // fungsi delay search
    function delay(fn, ms) {
        let timer = 0
        return function(...args) {
            clearTimeout(timer)
            timer = setTimeout(fn.bind(this, ...args), ms || 0)
        }
    }

    function loaddata(){
        $.ajax({
            url: "{{ url('get_lap') }}",
            method: "GET",
            success: function(response){
                ambildata(response);
            },
        });
    }

    function link_cetak(){
        let desa_kelurahan = $('#desa_kelurahan').val();
        let status = $('#status').val();
        let cetak = document.getElementById('cetak');
        cetak.href = "/cetak?desa_kelurahan=" + desa_kelurahan +"&status=" + status
    }

    function loading(){
        $('table tbody').empty();
        $('table tbody').append(`<tr>
            <td colspan="7" class="text-center" id="loading">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </td>
        </tr>`);
    }

    function ambildata(response){
        let data = response.data;
        let body = '';
        let no = 1;
        $('#loading').hide()
        $('table tbody').html('');
        if (data.length <= 0) {
            body = `
            <tr>
                <td colspan="7" class="text-center">
                    Belum ada data penerima bantuan
                </td>
            </tr>`
            $('table tbody').html(body); 
        } else {
            body = ''
            data.forEach((params) => {
                let tanggal = params.penduduk.tanggal_lahir;
                var hari = tanggal.substring(8,10)
                var bulan = tanggal.substring(7,5)
                var tahun = tanggal.substring(0,4)
                body = `
                <tr class="text-center">
                    <td>`+no+`</td>
                    <td>`+params.penduduk.nama+`</td>
                    <td>`+params.penduduk.nik+`</td>
                    <td>`+hari+`/`+bulan+`/`+tahun+`</td>
                    <td>`+params.penduduk.alamat_lengkap+`</td>
                    <td>`+params.penduduk.jekel+`</td>
                    <td>`+params.validasi+`</td>
                </tr>`
                $('table tbody').append(body);
                no++;
            });    
        }
    }

    $(document).on('change', '#desa_kelurahan', function(e){
        $.ajax({
            url: "{{ url('get_lap') }}",
            method: "GET",
            dataType: 'json',
            data: { 'dskl' : $(this).val() },
            success: function(response){
                ambildata(response);
                link_cetak();
            },
        });
    });
</script>

@endpush