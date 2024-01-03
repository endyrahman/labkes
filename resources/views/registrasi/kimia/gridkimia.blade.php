@php
    $i = ($data->currentpage()-1)* $data->perpage() + 1;
    $totalrow = $data->total();
@endphp
@foreach($data as $val)
<tr>
    <td class="text-center">{{ $i++ }}</td>
    <td>{{ $val->no_registrasi }}</td>
    <td>{{ $val->nama_sampel }}</td>
    <td>{{ $val->lokasi_sampel }}</td>
    <td class="text-center">
        <button class="btn btn-outline-info p-1 mb-1" data-toggle="modal" data-target="#mdlDetailPemeriksaan" onclick="getDetailPemeriksaan({{ $val->id }}, {{ $val->jmlh_sampel }}, {{ $val->jenis_lab_id }})">Lihat Pemeriksaan</button>
    </td>
    <td class="text-center">{{ date('d-m-Y H:i', strtotime($val->tgl_waktu_kunjungan)) }}</td>
    <td style="text-align: right;">{{ number_format($val->total_biaya, 0, ',', '.') }}</td>
    <td class="text-center">
        @if (!$val->status_bayar)
            <a href="{{ url('/pembayaran/laboratorium/create/'.$val->id) }}" class="btn btn-outline-danger p-1 mb-1">Konfirmasi Bayar</a>
        @elseif ($val->status_bayar == 1)
            <span class="shadow-none badge badge-warning">Proses Validasi</span>
        @elseif ($val->status_bayar == 2)
            <span class="shadow-none badge badge-success">Lunas</span>
        @endif
    </td>
    <td class="text-center">
        <a href="{{ url('/pencetakan/cetakbuktiregistrasi/'.$val->id) }}" title="Cetak Bukti Registrasi" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>&nbsp;
        @if (!$val->status_bayar)
            <button type="button" class="btn btn-danger btn-sm bs-tooltip p-1" onclick="hapusRegistrasi({{$val->id}})">Hapus</button>
            <a href="{{ url('/registrasi/kimia/edit/'.$val->id) }}" class="btn btn-warning btn-sm bs-tooltip p-1">Edit</a>
        @endif
        @if ($val->fileLaboratoriumHasilPemeriksaan)
            <button type="button" class="btn btn-success btn-sm p-1" onclick="bukaHasilPemeriksaan({{$val->id}}, '{{ $val->fileLaboratoriumHasilPemeriksaan }}')">Hasil</button>
        @endif
    </td>
</tr>
@endforeach
<tr>
    <td colspan="9">{{ $data->appends(['combocari' => Request::get('combocari'), 'pencarian' => Request::get('pencarian')])->render() }}</td>
</tr>

