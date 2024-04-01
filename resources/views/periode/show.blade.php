@extends('layout.main')
@section('container')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4 text-center text-dark">History Penerima Bantuan Sosial periode {{ $periode->periode }}</h5>
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

        function loaddata(){
            let id = "{{ $periode->id }}"
            $.ajax({
                url: "{{ url('json_dpp') }}/"+id,
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
                        <td>`+params.penduduk.jekel+`</td>
                        <td>`+params.validasi+`</td>
                    </tr>`
                    $('table tbody').append(body);
                    no++;
                });    
            }
        }
    </script>
@endpush