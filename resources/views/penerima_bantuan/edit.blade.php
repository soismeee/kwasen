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
                                <input type="text" class="form-control" name="nik" id="nik" placeholder="Masukan Nomor induk penduduk" value="{{ $data->penduduk->nik }}" readonly >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label>Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Pilih tanggal lahir" value="{{ $data->penduduk->tanggal_lahir }}">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Jenis Kelamin</label>
                                <select class="form-control" name="jekel" id="jekel">
                                    <option selected disabled>Pilih jenis kelamin</option>
                                    <option value="L" {{ $data->penduduk->jenis_kelamin == "L" ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $data->penduduk->jenis_kelamin == "P" ? 'selected' : '' }}>Perempuan</option>
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
                                <input type="text" class="form-control" name="penghasilan" id="penghasilan" value="Rp. {{ number_format($data->penghasilan,0,',','.') }}" placeholder="Masukan penghasilan">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option selected disabled>Pilih status</option>
                                    <option value="Disabilitas" {{ $data->status == "Disabilitas" ? 'selected' : '' }}>Disabilitas</option>
                                    <option value="Lansia" {{ $data->status == "Lansia" ? 'selected' : '' }}>Lansia</option>
                                    <option value="Ibu Hamil" {{ $data->status == "Ibu Hamil" ? 'selected' : '' }}>Ibu Hamil</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label>Anggota Polri/ASN</label>
                                <select name="polri_asn" id="polri_asn" class="form-control">
                                    <option disabled selected>Pilih Status</option>
                                    <option value="Ya" {{ $data->polri_asn == "Ya" ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ $data->polri_asn == "Tidak" ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label>Penerima Bansos Lain</label>
                                <select name="pbl" id="pbl" class="form-control">
                                    <option disabled selected>Pilih Status</option>
                                    <option value="Penerima" {{ $data->pbl == "Penerima" ? 'selected' : '' }}>Penerima</option>
                                    <option value="Bukan" {{ $data->pbl == "Bukan" ? 'selected' : '' }}>Bukan</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label>DTKS</label>
                                <select name="dtks" id="dtks" class="form-control">
                                    <option disabled selected>Pilih Status</option>
                                    <option value="Belum" {{ $data->dtks == "Belum" ? 'selected' : '' }}>Belum</option>
                                    <option value="Sudah" {{ $data->dtks == "Sudah" ? 'selected' : '' }}>Sudah</option>
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
    let penghasilan = document.getElementById("penghasilan");
    penghasilan.addEventListener("keyup", function(e) {
        penghasilan.value = convertRupiah(this.value, "Rp. ");
    });

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