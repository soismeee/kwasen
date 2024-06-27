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
        .tengah{
            text-align: center;
        }
        .border{
            padding: 3px 8px;
            border: 1px solid #3c3c3c;
        }
    </style>
    <table>
        
    </table>

    <table>
        <tr>
            <td colspan="2" style="text-align: left">
                <img src="/assets/img/logo_pekalongankab.svg" width="25%">
            </td>
            <td colspan="5" style="text-align: center">
                <h2>Laporan Penerimaan bantuan sosial PKH</h2>
                <P>Desa Kwasen Kecamatan Kesesi Kabupaten Pekalongan</P>
                <p>Periode : {{ $periode->periode }}</p>
            </td>
        </tr>
        <tr class="border">
            <th class="border" width="3%">No</th>
            <th class="border" width="15%">Nama</th>
            <th class="border" width="20%">NIK</th>
            <th class="border" width="12%">Tgl lahir</th>
            <th class="border" width="30%">Alamat</th>
            <th class="border" width="10%">Jenis Kelamin</th>
            <th class="border" width="10%">Status</th>
        </tr>
        <tbody>
            @foreach ($data as $item)
                <tr class="border">
                    <td class="border tengah">{{ $loop->iteration }}</td>
                    <td class="border">{{ $item->penduduk->nama }}</td>
                    <td class="border">{{ $item->penduduk_nik }}</td>
                    <td class="border tengah">{{ date('d/m/Y', strtotime($item->penduduk->tanggal_lahir)) }}</td>
                    <td class="border">{{ $item->penduduk->alamat_lengkap }}</td>
                    <td class="border">{{ $item->penduduk->jenis_kelamin == "L" ? "Laki - laki" : "Perempuan" }}</td>
                    <td class="border tengah">{{ $item->validasi }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5"></td>
                <td style="text-align: right" colspan="2">
                    <br />
                    <span>Kwasen, {{ date('d/m/Y') }} <br /></span>
                    <span>Kepala Desa Kwasen</span>
                    <br />
                    <br />
                    <br />
                    <br />
                    <span>(...............................)</span>
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    
</body>
</html>
<script>
    // print()
</script>
