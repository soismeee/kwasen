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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form id="form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="">Kode</label>
                                <input type="text" class="form-control" name="kode" id="kode" placeholder="Kode">
                                
                                <input type="hidden" class="form-control" name="id" id="id">
                                <input type="hidden" class="form-control" name="kec_id" id="kec_id" value="{{ $kecamatan->id }}">
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="">Nama desa/kelurahan</label>
                                <input type="text" class="form-control" name="nama_desakelurahan" id="nama_desakelurahan" placeholder="Desa/kelurahan">
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary tombol">Tambah Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Daftar desa/kelurahan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="desakelurahan" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th width="40%">Kode</th>
                                    <th width="40%">Desa/Kelurahan</th>
                                    <th width="10%">#</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr>
                                    <td colspan="4" class="text-center" id="loading">
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
            let kec_id = "{{ $kecamatan->id }}";
            $.ajax({
                url: "{{ url('get_sdkl') }}/"+kec_id,
                method: "GET",
                success: function(response){
                    $('#loading').hide();
                    $('table tbody').html('');
                    let data = response.data;
                    let no = 1;
                    data.forEach((params) => {
                        body = `
                        <tr class="text-center">
                            <td>`+no+`</td>
                            <td>`+params.kode+`</td>
                            <td>`+params.nama_desakelurahan+`</td>
                            <td> 
                                <a href="#" class="btn btn-sm btn-warning edit-data" data-id="`+params.id+`" title="Edit data" >Edit</a>
                            </td>
                        </tr>`
                        $('table tbody').append(body);
                        no++;
                    }); 
                },
                error: function(err){
                    $('table tbody').html(`
                    <tr>
                        <td colspan="4">`+err.responseJSON.message+`</td>
                    </tr>`);
                }
            })
        }

        $(document).on('submit', '#form', function(e){
            e.preventDefault();
            let data = $(this).serialize();
            console.log(data);
            $.ajax({
                url: "{{ url('save_dskl') }}",
                method: "POST",
                data: data,
                success: function(response){
                    $('#id').val('');
                    $('#kode').val('');
                    $('#nama_desakelurahan').val('');
                    $('.tombol').text('Tambah Data');
                    $('.tombol').removeClass('btn-warning');    
                    $('.tombol').addClass('btn-primary'); 
                    loaddata();
                },
                error: function(err){
                    console.log(err);
                }
            })
        });

        $(document).on('click', '.edit-data', function(e){
            let edit_id = $(this).data('id');
            $.ajax({
                url: "{{ url('getid_dskl') }}/"+ edit_id,
                method: "GET",
                success: function(response){
                    $('#id').val(response.data.id);
                    $('#kode').val(response.data.kode);
                    $('#nama_desakelurahan').val(response.data.nama_desakelurahan);
                    $('.tombol').text('Edit data');    
                    $('.tombol').removeClass('btn-primary');    
                    $('.tombol').addClass('btn-warning');    
                }
            });
        })
    </script>
@endpush