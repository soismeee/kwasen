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
            <div class="card">
                <div class="card-header">
                    <h5>Form pengajuan</h5>
                </div>
                <div class="card-body">
                    <form id="form_input">
                        <h4><strong>Data Penduduk</strong></h4>
                        <div class="alert alert-danger alert-solid peringatan" role="alert" style="display: none">Maaf, untuk saat ini anda tidak bisa menginput penerima bantuan</div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukan nama">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>NIK</label>
                                <input type="text" class="form-control" name="nik" id="nik" placeholder="Masukan Nomor induk penduduk" maxlength="16" onkeypress="return hanyaAngka(event)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label>Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Pilih tanggal lahir">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Jenis Kelamin</label>
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                    <option selected disabled>Pilih jenis kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat_lengkap" id="alamat_lengkap" cols="5" rows="3"></textarea>
                            </div>
                        </div>
                        <hr />
                        <h4><strong>Data Bantuan</strong></h4>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label>Penghasilan</label>
                                <input type="text" class="form-control" name="penghasilan" id="penghasilan" placeholder="Masukan penghasilan">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option disabled selected>Pilih Status</option>
                                    <option value="Disabilitas">Disabilitas</option>
                                    <option value="Lansia">Lansia</option>
                                    <option value="Ibu Hamil">Ibu Hamil</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label>Anggota Polri/ASN</label>
                                <select name="polri_asn" id="polri_asn" class="form-control">
                                    <option disabled selected>Pilih Status</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label>Penerima Bansos Lain</label>
                                <select name="pbl" id="pbl" class="form-control">
                                    <option disabled selected>Pilih Status</option>
                                    <option value="Penerima">Penerima</option>
                                    <option value="Bukan">Bukan</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label>DTKS</label>
                                <select name="dtks" id="dtks" class="form-control">
                                    <option disabled selected>Pilih Status</option>
                                    <option value="Belum">Belum</option>
                                    <option value="Sudah">Sudah</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-md btn-danger" id="tambah" disabled>Tambah ke list</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">List daftar pengajuan</h4>
                    <form id="form-penduduk">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th width="25%">Informasi seseorang</th>
                                        <th width="25%">Alamat</th>
                                        <th width="15%">Penghasilan</th>
                                        <th width="25%">Status</th>
                                        <th width="5%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>   
                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-lg btn-info tombol-simpan" id="tombol-simpan" style="display:none">Simpan data</button>
                        </div>  
                    </form> 
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
        let tanggal_periode = "{{ $tanggal_periode }}";
        let maksimal_penerima = "{{ $maksimal_penerima }}";
        if (tanggal_periode == "masuk") {
            $('#tambah').removeClass('btn-danger');
            $('#tambah').addClass('btn-primary');
            $('#tambah').prop('disabled', false);
            if (maksimal_penerima == "tidak") {
                $('#tambah').addClass('btn-danger');
                $('#tambah').removeClass('btn-primary');
                $('#tambah').prop('disabled', true);
                $('.peringatan').show();
            }
        }
    });

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

    function removeall(){
        arrayPenduduk = [];
        $('table tbody').html('');
        $('#tombol-simpan').hide();
    }

    function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))

        return false;
    return true;
    }

    let arrayPenduduk = []; // penyimpanan sementara data penduduk

    // fungsi untuk mengirim data ke dalam tabel ketika tombol tambah pengajuan di klik
    $(document).on('click', '#tambah', function(e) {
        e.preventDefault();
        
        // mengambil data select jenis kelamin
        var jenis_kelamin = document.getElementById("jenis_kelamin");
        var v_jenis_kelamin = jenis_kelamin.value;
        var t_jenis_kelamin = jenis_kelamin.options[jenis_kelamin.selectedIndex].text;

        // mengambil data status
        var status = document.getElementById("status");
        var v_status = status.value;
        var t_status = status.options[status.selectedIndex].text;

        // mengambil data Polri/ASn
        var polri_asn = document.getElementById("polri_asn");
        var v_polri_asn = polri_asn.value;
        var t_polri_asn = polri_asn.options[polri_asn.selectedIndex].text;

        // mengambil data Penerima bantuan lain
        var pbl = document.getElementById("pbl");
        var v_pbl = pbl.value;
        var t_pbl = pbl.options[pbl.selectedIndex].text;

        // mengambil data DTKS
        var dtks = document.getElementById("dtks");
        var v_dtks = dtks.value;
        var t_dtks = dtks.options[dtks.selectedIndex].text;
        

        // menyimpan inputan pada form ke dalam variabel data
        var data = {
            'nama': $('#nama').val(),
            'nik': $('#nik').val(),
            'tanggal_lahir': $('#tanggal_lahir').val(),
            'jenis_kelamin': v_jenis_kelamin,
            'alamat_lengkap': $('#alamat_lengkap').val(),

            'penghasilan': $('#penghasilan').val(),
            'status': v_status,
            'polri_asn': v_polri_asn,
            'pbl': v_pbl,
            'dtks': v_dtks,
        }

        // membuat valiasi data harus terisi
        if (!data.nama)
            return alert("Nama tidak boleh kosong");
        if (!data.nik)
            return alert("NIK tidak boleh kosong");
        if (data.nik.length < 16)
            return alert("masukan NIK dengan benar");
        if (!data.tanggal_lahir)
            return alert("Tanggal lahir tidak boleh kosong");
        if (data.jenis_kelamin == "Pilih jenis kelamin")
            return alert("Jenis kelamin harus di pilih");
        if (!data.alamat_lengkap)
            return alert("Alamat tidak boleh kosong");
        
        if (!data.penghasilan)
            return alert("Penghasilan harus diisi");
        if (data.status == "Pilih status")
            return alert("Status harus di pilih");
        if (data.polri_asn == "Pilih status")
            return alert("Status anggota polri/ASN harus di pilih");
        if (data.pbl == "Pilih status")
            return alert("Status Penerima bantuan lain harus di pilih");
        if (data.dtks == "Pilih status")
            return alert("Status DTKS harus di pilih");

        // membuat validasi nik sudah ada di tabel
        if (arrayPenduduk.filter(item => item.nik == data.nik).length > 0)
            return alert("NIK sudah ada");

        let get_penghasilan = data.penghasilan;
        let getpenghasilantreplace = get_penghasilan.replace(/[^,\d]/g, "");
        
        let nilai_penghasilan = getpenghasilantreplace <= "{{ $kriteria->penghasilan }}" ? 1 : 0;
        
        let nilai_polri_asn = v_polri_asn == "{{ $kriteria->polri_asn }}" ? 1 : 0;
        let nilai_pbl = data.pbl == "{{ $kriteria->pbl }}" ? 1 : 0;
        let nilai_dtks = data.dtks == "{{ $kriteria->dtks }}" ? 1 : 0;
        
        let validasi = "Tidak";
        let isi_validasi = "Tidak Diterima" 
        if (nilai_penghasilan+nilai_polri_asn+nilai_pbl+nilai_dtks > 1) {
            validasi = "Ya";   
            isi_validasi = "Diterima" 
        }

        console.log(validasi);
        let no = arrayPenduduk.length + 1;
        let html =
            '<tr>\
                <td style="text-align: center">'+ no +'</td>\
                <td>\
                    Nama : ' + data.nama + ' <br>\
                    NIK : ' + data.nik + ' <br>\
                    Tgl Lahir : ' + data.tanggal_lahir + ' <br>\
                    Jekel : ' + t_jenis_kelamin + ' <br>\
                    <input type="hidden" id="namas" name="namas[]" value="' + data.nama + '">\
                    <input type="hidden" id="niks" name="niks[]" value="' + data.nik + '">\
                    <input type="hidden" id="tanggal_lahirs" name="tanggal_lahirs[]" value="' + data.tanggal_lahir + '">\
                    <input type="hidden" id="jenis_kelamins" name="jenis_kelamins[]" value="' + data.jenis_kelamin + '">\
                </td>\
                <td>' + data.alamat_lengkap + '<input type="hidden" id="alamat_lengkaps" name="alamat_lengkaps[]" value="' + data.alamat_lengkap + '"></td>\
                <td>' + data.penghasilan + '<input type="hidden" id="penghasilans" name="penghasilans[]" value="' + data.penghasilan + '"></td>\
                <td>\
                    Status : ' + data.status + ' <br>\
                    Anggota Polri/ASN : ' + data.polri_asn + ' <br>\
                    PBL : ' + data.pbl + ' <br>\
                    DTKS : ' + data.dtks + ' <br>\
                    <input type="hidden" id="statuss" name="statuss[]" value="' + data.status + '">\
                    <input type="hidden" id="polri_asns" name="polri_asns[]" value="' + data.polri_asn + '">\
                    <input type="hidden" id="pbls" name="pbls[]" value="' + data.pbl + '">\
                    <input type="hidden" id="dtkss" name="dtkss[]" value="' + data.dtks + '">\
                    <input type="hidden" id="validasis" name="validasis[]" value="' + validasi + '">\
                </td>\
                <td><a data-nik="'+data.nik+'" type="button" class="action-icon remove-item"><i class="fas fa-trash"></i></a></td>\
            </tr>';
        arrayPenduduk.push({
            nik: data.nik,
        });
        $('table tbody').append(html);
        $('#form_input')[0].reset();
        $('.tombol-simpan').show()
    });

    $(document).on('click', '#tombol-simpan', function(e) {
        e.preventDefault();
        $('#tombol-simpan').addClass('disabled').html(`<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`);
        $.ajax({
            type: "POST",
            url: "{{ url('save') }}",
            data: $('#form-penduduk').serialize(),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                $('#tombol-simpan').removeClass('disabled').html('Simpan data');
                removeall();
            },
            error: function(err){
                alert("Tidak berhasil disimpan, silahkan ulangi atau refresh halaman");
                $('#tombol-simpan').removeClass('disabled').html('Simpan data');
            }
        });
    });

    $('.table-responsive table').on('click', '.remove-item', function() {
        if (arrayPenduduk.length == 0) return alert('Belum ada yang dipilih!');
        $(this).parent().parent().remove();
        let id = $(this).data('nik');
        arrayPenduduk = arrayPenduduk.filter(p => p.nik !== nik);
    });
</script>
@endpush