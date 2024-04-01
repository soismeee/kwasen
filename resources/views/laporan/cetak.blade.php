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
    <h2 class='tengah'>Laporan Penerimaan bantuan</h2>
    <P class='tengah'>Dinas Sosial Kabupaten Barang</P>
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
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->nik }}</td>
                    <td class="tengah">{{ date('d/m/Y', strtotime($item->tanggal_lahir)) }}</td>
                    <td>{{ $item->alamat_lengkap }}</td>
                    <td>{{ $item->jekel }}</td>
                    <td class="tengah">Proses</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br />
</body>
</html>
<script>
    print()
</script>
