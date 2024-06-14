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

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Penerima Bansos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $penerima_bantuan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paste fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Penduduk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $penduduk }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Daftar Penerima Bantuan Sosial</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            &nbsp;
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-end mb-3">
                                <input type="text" class="form-control" id="search" placeholder="Cari nama yang anda inginkan">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th width="20%">Nama</th>
                                    <th width="20%">NIK</th>
                                    <th width="10%">Tgl lahir</th>
                                    <th width="25%">Alamat</th>
                                    <th width="10%">Jenis Kelamin</th>
                                    <th width="10%">Status</th>
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
    </div>

</div>
<!-- /.container-fluid -->
@endsection
@push('js')
    <script>
        $(document).ready(function(e){
            loaddata();
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
                url: "{{ url('get_pb') }}",
                method: "GET",
                success: function(response){
                    ambildata(response);
                },
            });
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
                        <td>`+params.penduduk.jenis_kelamin+`</td>
                        <td>`+params.validasi+`</td>
                    </tr>`
                    $('table tbody').append(body);
                    no++;
                });    
            }
        }

        // fungsi search
        var search = document.getElementById("search");
        search.addEventListener("keyup", delay(function(e) {
            loading();
            var data = {
                'cari': $(this).val(),
            };
            $.ajax({
                type: "GET",
                url: "{{ url('search') }}",
                data: data,
                dataType: "json",
                success: function (response) {
                    ambildata(response)
                }
            });
        }, 1000));
    </script>
@endpush