@php
    $i = ($data->currentpage()-1)* $data->perpage() + 1;
    $totalrow = $data->total();
@endphp
@foreach($data as $val)
<tr>
    <td class="text-center">{{ $i++ }}</td>
    <td>{{ $val->no_registrasi }}</td>
    <td>{{ $val->nama_jenis_lab }}</td>
    <td class="text-center">{{ date('d-m-Y H:i', strtotime($val->tgl_waktu_kunjungan)) }}</td>
    <td class="text-center">
        <a href="{{ url('/storage/hasil_lab/'.$val->fileLaboratoriumHasilPemeriksaan) }}" class="btn btn-success btn-sm p-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Hasil Lab" target="_blank" onclick="return countCetakDownload({{$val->pemeriksaan_id}});"><span class="badge badge-danger counterHasil"></span> Hasil</a>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="8">{{ $data->appends(['combocari' => Request::get('combocari'), 'pencarian' => Request::get('pencarian')])->render() }}</td>
</tr>
