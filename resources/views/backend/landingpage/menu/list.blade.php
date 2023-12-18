@php
    $i = ($menu->currentpage()-1)* $menu->perpage() + 1;
    $totalrow = $menu->total();
@endphp
@foreach ($menu as $val)
<tr>
    <td class="text-center">{{ $i++ }}</td>
    <td>{{ $val->nama_menu }}</td>
    <td class="text-center">
        <a href="javascript:void(0);"  data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F39F5A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="10">
        Jumlah Data : {{ $totalrow }}
    </td>
</tr>
<tr>
    <td colspan="10">{{ $menu->appends(['combocariclosing' => Request::get('combocariclosing'), 'pencarianclosing' => Request::get('pencarianclosing')])->render() }}</td>
</tr>

