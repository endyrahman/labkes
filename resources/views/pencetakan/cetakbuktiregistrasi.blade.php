<html>
<head>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            color: #000;
            /*background:#dadada; */
            font-family: "Times New Roman", Times, serif;
        }

        p.head1 {
            font-weight: bold;
            font-size: 12pt;
            font-family: "Times New Roman", Times, serif;
            margin-bottom: 0;
        }

        p.head2 {
            font-size: 12pt;
            font-family: "Times New Roman", Times, serif;
        }

        p.head3 {
            font-size: 12pt;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
        }

        p.datahead {
            font-size: 12pt;
            font-family: "Times New Roman", Times, serif;
        }

        #header {
            padding: 5px 10px;
            /*background:#20B2AA;*/
            font-family: "Times New Roman", Times, serif;
        }

        .judul {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            font-family: "Times New Roman", Times, serif;
        }

        .normal {
            font-size: 12px;
            font-family: "Times New Roman", Times, serif;
        }

        #wrapper {
            width: 100%;
            margin: 0 auto;
            /*background:#FAFAD2;*/
        }

        #main {
            margin: 0 auto;
            margin: 30px;
            padding: 10px;
            border: 1px solid;
        }

        #sidebar {
            float: right;
            width: 66%;
            /*background:#FAFAD2;*/
            padding: 10px;
            border: 1px solid;
        }

        #footer {
            clear: both;
            /*background:#D8BFD8;*/
            padding: 5px 10px;
        }

        #navigation {
            padding: 5px 10px;
            /*background:#40E0D0;*/
        }

        #navigation ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        #navigation li {
            display: inline;
            margin: 0;
            padding: 0;
        }

        .tabel1 {
            border-collapse: collapse;
        }

        .tabel1, .tabel1 th {
            border: 1px solid black;
        }

        tbody:before, tbody:after {
            display: none;
        }

        td {
            vertical-align: top;
        }

        @page {
            size: letter portrait;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="navigation"></div>
    <div id="main">
        <center>
            <table width=100%>
                <tr>
                    <td width=20%>
                        <center>
                        </center>
                    </td>
                    <td>
                        <center>
                            <p class="head1">DINAS KESEHATAN KOTA SEMARANG<br>
                                UPTD LABORATORIUM KESEHATAN
                            <p>
                                Alamat: Jl. WR Supratman RT. 01/RW. 06, Kelurahan Gisikdrono, Semarang Barat Telp. (024) 76436457
                        </center>
                    </td>
                </tr>
            </table>
            <hr>
            <p style="font-size: 16px;"> Tanda Bukti Registrasi Online Labkes </p>
        </center>
        <center>
            <div>
                <img src="data:image/png;base64, {!! $qrcode !!}">
            </div><br/>
            <div class="mt-2">
                <table style="width:85%;margin:0 auto;">
                    <tr>
                        <td style="width:25%;">No. Registrasi</td>
                        <td style="width:3%;">:</td>
                        <td style="width:72%;">{{ $data->no_registrasi }}</td>
                    </tr>
                    <tr>
                        <td><b>Jadwal</b></td>
                        <td><b>:</b></td>
                        <td><b>{{ date('d-m-Y H:i', strtotime($data->tgl_waktu_kunjungan)) }}</b></td>
                    </tr>
                    <tr>
                        <td>No. Pelanggan</td>
                        <td>:</td>
                        <td>{{ str_pad($data->user_id, 6, '0', STR_PAD_LEFT);  }}</td>
                    </tr>
                    @if ($data->jenis_lab_id == 1)
                        <tr>
                            <td>Nama Pasien</td>
                            <td>:</td>
                            <td>{{ $data->nama_pasien }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>{{ $data->jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <td>Tgl. Lahir</td>
                            <td>:</td>
                            <td>{{ date('d-m-Y', strtotime($data->tgl_lahir)) }}</td>
                            <td></td>
                        </tr>
                    @else
                        <tr>
                            <td>Lokasi Sampel</td>
                            <td>:</td>
                            <td>{{ $data->lokasi_sampel }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Sampel</td>
                            <td>:</td>
                            <td>{{ $data->jmlh_sampel }}</td>
                            <td></td>
                        </tr>
                    @endif
                    <tr>
                        <td>Pemeriksaan</td>
                        <td>:</td>
                        <td>
                            @foreach ($dtPemeriksaan as $val)
                                {{ $val->nama_pemeriksaan }}, 
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td>Biaya</td>
                        <td>:</td>
                        <td>
                            Rp. {{ number_format($data->total_biaya, 0, ',', '.') }}, -
                        </td>
                    </tr>
                </table>
            </div>
            <h4>Datang sesuai dengan jadwal registrasi yang sudah dipilih</h4>
        </center>
    </div>
    <div style="padding-left:20;">
        <table >
            <tr>
                <td colspan="3">Pembayaran melalui rekening berikut :</td>
            </tr>
            <tr>
                <td>Nama Bank</td>
                <td style="text-align: center;">:</td>
                <td style="text-align: left;">Bank BPD Jawa Tengah</td>
            </tr>
            <tr>
                <td>Nama Pemilik</td>
                <td style="text-align: center;">:</td>
                <td style="text-align: left;">UPTD Labkes Kota Semarang</td>
            </tr>
            <tr>
                <td>Nomor Rekening</td>
                <td style="text-align: center;">:</td>
                <td style="text-align: left;">20540603051</td>
            </tr>
        </table>
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>
