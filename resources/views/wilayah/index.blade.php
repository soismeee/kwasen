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

        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-3">Daftar Kecamatan</h5>
                        <a href="{{ url('create_kec') }}" class="btn btn-md btn-success">+ Tambah Kecamatan</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="periode" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th width="30%">Kode</th>
                                    <th width="30%">Kecamatan</th>
                                    <th width="20%">Desa/Kelurahan</th>
                                    <th width="10%">#</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
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
        const table = $('#periode').DataTable({          
            "lengthMenu": [[5, 10, 25, 50, 100, -1],[5, 10, 25, 50, 100, 'All']],
            "pageLength": 10, 
            processing: true,
            serverSide: true,
            responseive: true,
            ajax: {
                url:"{{ url('json_kec') }}",
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
                    return row.kode
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return row.nama_kecamatan
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return `<a href="{{ url('/list_sdkl') }}/`+row.id+`" class="btn btn-sm btn-primary">Daftar desa/kelurahan</a>`
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return `
                        <a href="{{ url('edit_kec') }}/`+row.id+`" class="btn btn-sm btn-success mb-2" title="Show data">Ubah</a>
                        <form action="{{ url('del_kec') }}/`+row.id+`" method="post">
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