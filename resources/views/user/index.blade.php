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

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Data Pengguna</h5>
                <a href="{{ route('usr.create') }}" class="btn btn-md btn-success">+ Tambah Pengguna</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="data-pengguna" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Nama</th>
                            <th width="30%">Username</th>
                            <th width="15%">Role</th>
                            <th width="10%">Status</th>
                            <th width="10%">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('user.modal_status')
</div>
<!-- /.container-fluid -->
@endsection

@push('js')
    <!-- Page level plugins -->
    <script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        // load data table
        const table = $('#data-pengguna').DataTable({          
            "lengthMenu": [[5, 10, 25, 50, 100, -1],[5, 10, 25, 50, 100, 'All']],
            "pageLength": 10, 
            processing: true,
            serverSide: true,
            responseive: true,
            ajax: {
                url:"{{ url('json_p') }}",
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
                    return row.name
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    return row.username
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    if (row.role == 1) {
                        return '<span class="badge badge-primary">Dinas</span>'
                    } else {
                        return '<span class="badge badge-dark">Desa/Kelurahan</span>'   
                    }
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                    if (row.status == 1) {
                        return '<span class="badge badge-success">Aktif</span>'
                    } else {
                        return '<span class="badge badge-danger">Tidak aktif</span>'   
                    }
                    }
                },
                {
                    "targets": "_all",
                    "defaultContent": "-",
                    "render": function(data, type, row, meta){
                        if (row.status == 1) {
                            return `
                                <a href="{{ route('usr.index') }}/`+row.id+`/edit" class="btn btn-warning edit-pengguna" title="Edit data" >Edit</a>
                                <a href="#" class="btn btn-danger setnonaktif" data-id="`+row.id+`" title="edit status" >NonAktifkan</a>
                            `   
                        } else {
                            return `
                                <a href="{{ route('usr.index') }}/`+row.id+`/edit" class="btn btn-warning edit-pengguna" title="Edit data" >Edit</a>
                                <a href="#" class="btn btn-success setaktif" data-id="`+row.id+`" title="edit status" >Aktifkan</a>
                            `                        
                        }
                    }
                },
            ]
        });

        $(document).on('click', '.setnonaktif', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            $('#id').val(id);
            $('#status').val(0);
            $('.keterangan').text('Apakah anda yakin ingin menonaktifkan pengguna ini?');
            $('.tombol').removeClass('btn-success');
            $('.tombol').addClass('btn-danger');
            $('.tombol').text('Nonaktifkan');
            $('#modalStatus').modal('show');
        });

        $(document).on('click', '.setaktif', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            $('#id').val(id);
            $('#status').val(1);
            $('.keterangan').text('Apakah anda yakin ingin mengaktifkan pengguna ini?');
            $('.tombol').addClass('btn-success');
            $('.tombol').removeClass('btn-danger');
            $('.tombol').text('Aktifkan');
            $('#modalStatus').modal('show');
        });

        $(document).on('click', '.tombol', function(e){
            e.preventDefault();
            let idstatus = $('#id').val();
            $.ajax({
                url: "{{ url('status') }}/"+idstatus,
                method: "PATCH",
                data: $('#form_status').serialize(),
                dataType: "json",
                success: function(response){
                    table.ajax.reload();
                    alert(response.message);
                },
                error: function(err){
                    alert(err.messageJSON.message);
                }
            });
        });
    </script>
@endpush