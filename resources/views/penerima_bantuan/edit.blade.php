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
        <div class="col-lg-12 mb-4">
            <form action="{{ url('update_data') }}/{{ $data->id }}" method="POST">
                @csrf
                @method('patch')
                <div class="card">
                    <div class="card-header">
                        <h5>Form edit pengajuan</h5>
                    </div>
                    <div class="card-body">
                        <h4><strong>Data Penduduk</strong></h4>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukan nama" value="{{ $data->penduduk->nama }}">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>NIK</label>
                                <input type="text" class="form-control" name="nik" id="nik" placeholder="Masukan Nomor induk penduduk" value="{{ $data->penduduk->nik }}" maxlength="16" onkeypress="return hanyaAngka(event)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label>Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Pilih tanggal lahir" value="{{ $data->penduduk->tanggal_lahir }}">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label>Jenis Kelamin</label>
                                <select class="form-control" name="jekel" id="jekel">
                                    <option selected disabled>Pilih jenis kelamin</option>
                                    <option value="Laki-laki" {{ $data->penduduk->jekel == "Laki-laki" ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ $data->penduduk->jekel == "Perempuan" ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label>Desa/Kelurahan</label>
                                <select class="form-control" name="nama_desa_kelurahan" id="nama_desa_kelurahan">
                                    <option selected disabled>Pilih desa/kelurahan</option>
                                    @foreach ($dskl as $item)
                                        <option value="{{ $item->id }}" {{ $item->id ==  $data->penduduk->desa_kelurahan_id ? 'selected' : ''}}>{{ $item->nama_desakelurahan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat_lengkap" id="alamat_lengkap" cols="5" rows="3">{{ $data->penduduk->alamat_lengkap }}</textarea>
                            </div>
                        </div>
                        <hr />
                        <h4><strong>Data Bantuan</strong></h4>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label>Penghasilan orang tua / wali</label>
                                <input type="text" class="form-control" name="penghasilan_ortu" id="penghasilan_ortu" value="Rp. {{ number_format($data->penghasilan_ortu,0,',','.') }}" placeholder="Masukan penghasilan">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option selected disabled>Pilih status</option>
                                    <option value="Yatim" {{ $data->status == "Yatim" ? 'selected' : '' }}>Yatim</option>
                                    <option value="Piatu" {{ $data->status == "Piatu" ? 'selected' : '' }}>Piatu</option>
                                    <option value="Yatim Piatu" {{ $data->status == "Yatim Piatu" ? 'selected' : '' }}>Yatim Piatu</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-md btn-primary">Ubah data</button>
                                <a href="{{ url('pb') }}" class="btn btn-danger">Kembali</a>
                            </div>
                        </div>
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
    let penghasilan_ortu = document.getElementById("penghasilan_ortu");
    penghasilan_ortu.addEventListener("keyup", function(e) {
        penghasilan_ortu.value = convertRupiah(this.value, "Rp. ");
    });

    function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))

        return false;
    return true;
    }

    function convertRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? prefix + rupiah : "";
    }
    </script>
@endpush