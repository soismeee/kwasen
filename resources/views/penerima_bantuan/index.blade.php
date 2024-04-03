@extends('layout.main')
@push('css')
        <!-- Custom styles for this page -->
        {{-- <link href="/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}
@endpush
@section('container')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="mb-3">Daftar benerima bantuan</h4> --}}
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data-penerima_bantuan" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th width="20%">Nama</th>
                                    <th width="15%">NIK</th>
                                    <th width="10%">Tgl lahir</th>
                                    <th width="25%">Alamat</th>
                                    <th width="10%">Jenis Kelamin</th>
                                    <th width="10%">Status</th>
                                    <th width="15">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center" id="loading">
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
    <!-- Page level plugins -->
    {{-- <script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script> --}}

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
                    let jenis_kelamin = params.penduduk.jenis_kelamin == "L" ? "Laki-laki" : "Perempuan";
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
                        <td>`+jenis_kelamin+`</td>
                        <td>`+params.validasi+`</td>
                        <td>
                            <a href="{{ url('spb') }}/`+params.id+`" class="btn btn-sm btn-info" title="Show data" >Detail</a> <br /> 
                            <a href="{{ url('epb') }}/`+params.penduduk.nik+`/edit" class="btn btn-sm btn-warning edit-pengguna" title="Edit data" >Edit</a>
                        </td>
                    </tr>`
                    $('table tbody').append(body);
                    no++;
                });    
            }
        }
    </script>

    
@endpush