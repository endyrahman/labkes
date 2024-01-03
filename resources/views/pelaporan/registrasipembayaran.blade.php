<!DOCTYPE html>
<html>
<head>
    <title>Laporan Registrasi</title>
</head>
<body>
    <p>Laporan Registrasi Laboratorium Kesehatan</p><br/>
    <p>Tanggal Cetak : {{ date('d-m-Y') }}</p><br/>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>No. Registrasi</th>
                <th>Laboratorium</th>
                <th>Tgl. Registrasi</th>
                <th>Tgl. Bayar</th>
                <th>Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1 @endphp
            @foreach($laporan as $val)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $val->nama_lengkap }}</td>
                <td>{{ $val->no_registrasi }}</td>
                <td>{{ $val->nama_jenis_lab }}</td>
                <td></td>
                <td></td>
                <td>{{ $val->total_biaya }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>