@php
    $i = ($data->currentpage()-1)* $data->perpage() + 1;
    $totalrow = $data->total();
@endphp
@foreach($data as $val)
<tr>
    <td class="text-center">{{ $i++ }}</td>
    <td>{{ $val->nama_jenis_lab }}</td>
    <td>{{ $val->nama_pemeriksaan }}</td>
    <td class="text-center">
        <button class="btn btn-outline-info p-1 mb-1" data-toggle="modal" data-target="#mdlDetailPemeriksaan" onclick="getDetailPaketPemeriksaan({{ $val->detail }})">Lihat Detail</button>
    </td>
    <td class="text-center">
        @if ($val->status)
            <span class="shadow-none badge badge-success">Aktif</span>
        @elseif ($val->status_bayar == 2)
            <span class="shadow-none badge badge-danger">Tdk Aktif</span>
        @endif
    </td>
    <td class="text-center">
        <a href="{{ url('/spr/master/paketpemeriksaan/'.$val->paket_pemeriksaan_id.'/edit/') }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Data"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="8">{{ $data->appends(['combocari' => Request::get('combocari'), 'pencarian' => Request::get('pencarian')])->render() }}</td>
</tr>
