@php
    $i = ($promosi->currentpage()-1)* $promosi->perpage() + 1;
    $totalrow = $promosi->total();
@endphp
@foreach ($promosi as $val)
<tr>
    <td class="text-center">{{ $i++ }}</td>
    <td>{{ $val->nama }}</td>
    <td class="text-center">{{ date('d-m-Y', strtotime($val->created_at)) }}</td>
    <td class="text-center">
        <div class="d-flex">
            <div class="usr-img-frame mr-2">
                <img class="img-fluid" src="{{ url('/storage/foto_promosi/'.$val->nama_file) }}">
            </div>
        </div>
    </td>
    <td class="text-center">
        @if ($val->status == 1)
            <span class="shadow-none badge badge-info">Aktif</span>
        @else
            <span class="shadow-none badge badge-danger">Tidak Aktif</span>
        @endif
    </td>
    <td class="text-center">
        <a href="{{ url('/spr/landingpage/promosi/'.$val->id.'/edit') }}"  data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F39F5A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="10">
        Jumlah Data : {{ $totalrow }}
    </td>
</tr>
<tr>
    <td colspan="10">{{ $promosi->appends(['combocariclosing' => Request::get('combocariclosing'), 'pencarianclosing' => Request::get('pencarianclosing')])->render() }}</td>
</tr>

