@php
    $i = ($data->currentpage()-1)* $data->perpage() + 1;
    $totalrow = $data->total();
@endphp
@foreach($data as $val)
<tr>
    <td class="text-center">{{ $i++ }}</td>
    <td class="text-center">{{ $val->no_registrasi }}</td>
    <td>{{ $val->nama_pasien }}</td>
    <td class="text-center">{{ date('d-m-Y H:i', strtotime($val->tgl_waktu_kunjungan)) }}</td>
    <td>
        @if ($val->jenis_lab_id == 1)
            Klinik
        @elseif ($val->jenis_lab_id == 2)
            Kimia
        @elseif ($val->jenis_lab_id == 3)
            Mikrobiologi
        @endif
    </td>
    <td style="text-align:right;">
        {{ number_format($val->total_biaya, 0, ",", ".") }}
    </td>
    <td class="text-center">
        @if ($val->status_bayar == '')
            <span class="badge outline-badge-danger shadow-none">Belum Bayar</span>
        @elseif ($val->status_bayar == 1)
            <span class="badge outline-badge-warning shadow-none">Menunggu Validasi</span>
        @elseif ($val->status_bayar == 2)
            <span class="badge outline-badge-success shadow-none">Lunas</span>
        @endif

        @if ($val->status == 2)
            <span class="badge outline-badge-primary shadow-none">Hasil Terupload</span>
        @endif
    </td>
    <td class="text-center">{{ date('d-m-Y', strtotime($val->created_at)) }} <br/> {{ date('H:i:s', strtotime($val->created_at)) }}</td>
    <td>
        <a href="{{ url('/pencetakan/cetakbuktiregistrasi/'.$val->id) }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Bukti Registrasi" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>

        <a href="javascript:void(0);" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Detail Pemeriksaan" onclick="mdlDetailPemeriksaan({{$val->id}}, '{{$val->no_registrasi}}', {{$val->jenis_lab_id}}, '{{$val->user_id}}')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="green" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg></a>

        @if ($val->status_bayar == 1)
            <a href="javascript:void(0)" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Validasi Pembayaran" onclick="mdlValidasiBayar({{$val->id}})"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="orange" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg></a>

        @elseif ($val->status_bayar == 2 and $val->status == 1)
            <a href="javascript:void(0);" onclick="mdlUploadHasilLab({{$val->id}})" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Upload Hasil"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></a>

        @endif
    </td>
</tr>
@endforeach
<tr>
    <td colspan="9">{{ $data->appends(['combocari' => Request::get('combocari'), 'pencarian' => Request::get('pencarian')])->render() }}</td>
</tr>