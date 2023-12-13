@php
    $i = ($pasien->currentpage()-1)* $pasien->perpage() + 1;
    $totalrow = $pasien->total();
@endphp
@foreach($pasien as $val)
<tr>
    <td class="text-center">{{ $i++ }}</td>
    <td>{{ $val->nik }}</td>
    <td>{{ $val->nama_pasien }}</td>
    <td class="text-center">{{ date('d-m-Y', strtotime($val->tgl_lahir)) }}</td>
    <td class="text-center">
        <a href="/pasien/{{$val->id}}/edit" class="btn btn-warning btn-sm p-1">Edit</a>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="9">{{ $pasien->appends(['combocari' => Request::get('combocari'), 'pencarian' => Request::get('pencarian')])->render() }}</td>
</tr>

