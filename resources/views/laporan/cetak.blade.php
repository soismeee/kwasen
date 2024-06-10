<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
</head>
<body>
    <style type="text/css">
        body{
        font-family: sans-serif;
        }
        table{
        margin: 20px auto;
        border-collapse: collapse;
        }
        table th,
        table td{
        border: 1px solid #3c3c3c;
        padding: 3px 8px;
        }
        .tengah{
            text-align: center;
        }
    </style>
    <h2 class='tengah'>Laporan Penerimaan bantuan sosial PKH</h2>
    <P class='tengah'>Desa Kwasen Kecamatan Kesesi Kabupaten Pekalongan</P>
    <p class="tengah">Periode : {{ $periode->periode }}</p>
    <table>
        <tr>
            <th width="3%">No</th>
            <th width="20%">Nama</th>
            <th width="20%">NIK</th>
            <th width="10%">Tgl lahir</th>
            <th width="27%">Alamat</th>
            <th width="10%">Jenis Kelamin</th>
            <th width="10%">Status</th>
        </tr>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td class="tengah">{{ $loop->iteration }}</td>
                    <td>{{ $item->penduduk->nama }}</td>
                    <td>{{ $item->penduduk_nik }}</td>
                    <td class="tengah">{{ date('d/m/Y', strtotime($item->penduduk->tanggal_lahir)) }}</td>
                    <td>{{ $item->penduduk->alamat_lengkap }}</td>
                    <td>{{ $item->penduduk->jenis_kelamin == "L" ? "Laki - laki" : "Perempuan" }}</td>
                    <td class="tengah">{{ $item->validasi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br />
    Kwasen, {{ date('d/m/Y') }} <br />
    Kepala Desa Kwasen
    <br />
    <br />
    <br />
    <br />
    (...............................)
</body>
</html>
<script>
    // print()
</script>
