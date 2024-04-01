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

    <!-- Content Row -->
    <div class="row">
        @if (auth()->user()->role == 1)
        <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <td width="50%"><h4><strong>Jumlah data</strong></h4></td>
                            <td width="50%"><h4><strong>: {{ $totaldata }}</strong></h4></td>
                        </tr>
                        {{-- <tr>
                            <td colspan="2" class="text-center">Pembagian data berdasarkan validasi ya dan tidak</td>
                        </tr>
                        <tr class="text-center">
                            <td><strong>YA</strong></td>
                            <td><strong>TIDAK</strong></td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                {{ $totalya }} <br />
                                P(Ya) = {{ $totalya }}/{{ $totaldata }} <br />
                                {{ round($totalya/$totaldata, 2) }}
                            </td>
                            <td>
                                {{ $totaltidak }} <br />
                                P(Ya) = {{ $totaltidak }}/{{ $totaldata }} <br />
                                {{ round($totaltidak/$totaldata, 2) }}
                            </td>
                        </tr> --}}
                    </table>
                </div>
            </div>
        </div>

        @endif
        {{-- <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_hasil" class="table table-bordered">
                            <tr>
                                <td>No</td>
                                <td>Penduduk</td>
                                <td>YA</td>
                                <td>TIDAK</td>
                                <td>Hasil</td>
                            </tr>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

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
                                    <th width="20%">Alamat</th>
                                    <th width="10%">Jenis Kelamin</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">#</th>
                                </tr>
                            </thead>
                            <tbody>
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
    <script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        // load data table
        const table = $('#data-penerima_bantuan').DataTable({          
            "lengthMenu": [[5, 10, 25, 50, 100, -1],[5, 10, 25, 50, 100, 'All']],
            "pageLength": 10, 
            processing: true,
            serverSide: true,
            responseive: true,
            ajax: {
                url:"{{ url('json_pb') }}",
                type:"POST",
                data:function(d){
                    d._token = "{{ csrf_token() }}"
                }
            },
            columns:[
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return row.penduduk.nama
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return row.penduduk.nik
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return row.penduduk.tanggal_lahir
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return row.penduduk.alamat_lengkap
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return row.penduduk.jekel
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return row.validasi
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return `
                        <a href="{{ url('spb') }}/`+row.id+`" class="btn btn-sm btn-success mb-2" title="Show data">Status</a>
                        <form action="{{ url('del_p') }}/`+row.penduduk.id+`" method="post">
                        @method('delete')
                        @csrf
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus data ini?')" title="Hapus data">Hapus</button>
                        </form>
                        `
                    }
                },
            ]
        });
    </script>
@endpush