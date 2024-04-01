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
                        <div class="col-lg-4 mb-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Pilih tanggal lahir">
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Jenis Kelamin</label>
                            <select class="form-control" name="jekel" id="jekel">
                                <option selected disabled>Pilih jenis kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label>Desa/Kelurahan</label>
                            <input type="text" class="form-control" value="{{ $kecamatan->nama_kecamatan }} - {{ $dskl->nama_desakelurahan }}" readonly>
                            <input type="hidden" name="nama_desa_kelurahan" id="nama_desa_kelurahan" value="{{ $dskl->id }}">
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
                            <label>Penghasilan orang tua atau wali</label>
                            <input type="text" class="form-control" name="penghasilan_ortu" id="penghasilan_ortu" placeholder="Masukan penghasilan orang tua atau wali">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Status</label>
                            <select class="form-control" name="status" id="status">
                                <option selected disabled>Pilih status</option>
                                <option value="Yatim">Yatim</option>
                                <option value="Piatu">Piatu</option>
                                <option value="Yatim Piatu">Yatim Piatu</option>
                            </select>
                        </div>
                        {{-- <div class="col-lg-12 mb-3">
                            <label>KIA</label>
                            <input type="checkbox" class="form-input" id="kia" name="kia" value="ya">
                        </div> --}}
                        <div class="col-lg-12">
                            <button class="btn btn-md btn-danger" id="tambah" disabled>Tambah ke list</button>
                        </div>
                    </div>
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
                                        <th width="3%">No</th>
                                        <th width="13%">Nama</th>
                                        <th width="12%">NIK</th>
                                        <th width="10%">Tgl lahir</th>
                                        <th width="10%">Jenis Kelamin</th>
                                        <th width="15%">Alamat</th>
                                        <th width="12%">Penghasilan ortu</th>
                                        <th width="10%">Status</th>
                                        <th width="5%">KIA</th>
                                        <th width="5%">Validasi</th>
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
        console.log(maksimal_penerima);
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

    let penghasilan_ortu = document.getElementById("penghasilan_ortu");
    penghasilan_ortu.addEventListener("keyup", function(e) {
        penghasilan_ortu.value = convertRupiah(this.value, "Rp. ");
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

    function getUsia(tanggal){
        var today = new Date();
        var birthday  = new Date(tanggal)
        var year = 0;
        if (today.getMonth() < birthday.getMonth()) {
            year = 1;
        } else if ((today.getMonth() == birthday.getMonth()) && today.getDate() < birthday.getDate()) {
            year = 1;
        }
        var age = today.getFullYear() - birthday.getFullYear() - year;

        if(age < 0){age = 0;}
        return age
    }

    let arrayPenduduk = []; // penyimpanan sementara data barang

    // fungsi untuk mengirim data ke dalam tabel ketika tombol tambah produk di klik
    $(document).on('click', '#tambah', function(e) {
        e.preventDefault();
        
        // mengambil data select jenis kelamin
        var jekel = document.getElementById("jekel");
        var v_jekel = jekel.value;
        var t_jekel = jekel.options[jekel.selectedIndex].text;

        // mengambil data nama desa kelurahan;

        // var v_nama_desa_kelurahan = nama_desa_kelurahan.value;
        // var t_nama_desa_kelurahan = nama_desa_kelurahan.options[nama_desa_kelurahan.selectedIndex].text;

        // mengambil data status
        var status = document.getElementById("status");
        var v_status = status.value;
        var t_status = status.options[status.selectedIndex].text;

        // membuat kondisi status kia
        // let kia = document.getElementById("kia");
        // let v_kia = '';
        // if (kia.checked) {
        //     v_kia = "ya"
        // } else {
        //     v_kia = "tidak"
        // }

        let kia_new = getUsia($('#tanggal_lahir').val());
        let v_kia = kia_new <= "{{ $kriteria->usia }}" ? "ya" : "tidak";
        

        // menyimpan inputan pada form ke dalam variabel data
        var data = {
            'nama': $('#nama').val(),
            'nik': $('#nik').val(),
            'tanggal_lahir': $('#tanggal_lahir').val(),
            'jekel': v_jekel,
            'nama_desa_kelurahan': $('#nama_desa_kelurahan').val(),
            'alamat_lengkap': $('#alamat_lengkap').val(),
            'penghasilan_ortu': $('#penghasilan_ortu').val(),
            'status': v_status,
            'kia': v_kia,
        }

        // membuat valiasi data harus terisi
        if (!data.nama)
            return alert("Nama tidak boleh kosong");
        if (!data.nik)
            return alert("NIK tidak boleh kosong");
        if (data.nik.length < 16)
            return alert("masukan NIK dengan benar");
        if (!data.tanggal_lahir)
            return alert("Tanggal lahir harus dipilih");
        if (data.jekel == "Pilih jenis kelamin")
            return alert("Jenis kelamin harus di pilih");
        if (data.nama_desa_kelurahan == "Pilih desa/kelurahan")
            return alert("Desa/kelurahan harus di pilih");
        if (!data.alamat_lengkap)
            return alert("Alamat tidak boleh kosong");
        if (!data.penghasilan_ortu)
            return alert("Penghasilan orang tua harus diisi");
        if (data.status == "Pilih status")
            return alert("Status harus di pilih");
        
        // membuat validasi nik sudah ada di tabel
        if (arrayPenduduk.filter(item => item.nik == data.nik).length > 0)
        return alert("NIK sudah ada");
        
        let get_usia = getUsia(data.tanggal_lahir);
        
        let get_pot = data.penghasilan_ortu;
        let getpotreplace = get_pot.replace(/[^,\d]/g, "");
        
        let nilai_pot = getpotreplace <= "{{ $kriteria->penghasilan_ortu }}" ? 1 : 0;
        let nilai_kia = v_kia == "{{ $kriteria->kia }}" ? 1 : 0;
        let nilai_usia = get_usia <= "{{ $kriteria->usia }}" ? 1 : 0;
        
        let validasi = "Tidak";
        let isi_validasi = "Tidak Diterima" 
        if (nilai_pot+nilai_kia+nilai_usia > 1) {
            validasi = "Ya";   
            isi_validasi = "Diterima" 
        }
        
        let no = arrayPenduduk.length + 1;
        let html =
            '<tr>\
                <td style="text-align: center">'+ no +'</td>\
                <td>' + data.nama + '<input type="hidden" id="namas" name="namas[]" value="' + data.nama + '"></td>\
                <td>' + data.nik + '<input type="hidden" id="niks" name="niks[]" value="' + data.nik + '"></td>\
                <td>' + data.tanggal_lahir + '<input type="hidden" id="tanggal_lahirs" name="tanggal_lahirs[]" value="' + data.tanggal_lahir + '"></td>\
                <td>' + data.jekel + '<input type="hidden" id="jekels" name="jekels[]" value="' + data.jekel + '"></td>\
                <td>' + data.alamat_lengkap + '<input type="hidden" id="alamat_lengkaps" name="alamat_lengkaps[]" value="' + data.alamat_lengkap + '"><input type="hidden" id="nama_desa_kelurahans" name="nama_desa_kelurahans[]" value="' + data.nama_desa_kelurahan + '"></td>\
                <td>' + data.penghasilan_ortu + '<input type="hidden" id="penghasilan_ortus" name="penghasilan_ortus[]" value="' + data.penghasilan_ortu + '"></td>\
                <td>' + data.status + '<input type="hidden" id="statuss" name="statuss[]" value="' + data.status + '"></td>\
                <td class="text-center">' + data.kia + '<input type="hidden" id="kias" name="kias[]" value="' + data.kia + '"></td>\
                <td class="text-center">' + isi_validasi + '<input type="hidden" id="validasi" name="validasi[]" value="' + validasi + '"></td>\
                <td><a data-nik="'+data.nik+'" type="button" class="action-icon remove-item"><i class="fas fa-trash"></i></a></td>\
            </tr>';
        arrayPenduduk.push({
            nik: data.nik,
        });
        $('table tbody').append(html);
        $('#nama').val(null)
        $('#nik').val(null)
        $('#tanggal_lahir').val(null)
        $('#jekel').val("Pilih jenis kelamin")
        // $('#nama_desa_kelurahan').val("Pilih desa/kelurahan")
        $('#alamat_lengkap').val(null)
        $('#penghasilan_ortu').val(null)
        $('#status').val("Pilih status")
        // $('#kia').prop('checked', false)
        $('.tombol-simpan').show()
    });

    $(document).on('click', '#tombol-simpan', function(e) {
        e.preventDefault();
        $('#tombol-simpan').addClass('disabled');
        $('#tombol-simpan').html(`<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>`);
        $.ajax({
            type: "POST",
            url: "{{ url('save') }}",
            data: $('#form-penduduk').serialize(),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                $('#tombol-simpan').removeClass('disabled');
                $('#tombol-simpan').html('Simpan data');
                removeall();
            },
            error: function(err){
                console.log(err);
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