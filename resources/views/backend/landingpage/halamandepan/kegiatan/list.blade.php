@php
    $i = ($kegiatan->currentpage()-1)* $kegiatan->perpage() + 1;
    $totalrow = $kegiatan->total();
@endphp
@foreach ($kegiatan as $val)
<tr>
    <td class="text-center">{{ $i++ }}</td>
    <td>{{ $val->nama_kegiatan }}</td>
    <td style="max-width: 600px">{{ $val->keterangan }}</td>
    <td>{{ date('d-m-Y', strtotime($val->tgl_kegiatan)) }}</td>
    <td>
        <div class="d-flex">
            <div class="usr-img-frame mr-2 rounded-circle">
                <img alt="avatar" class="img-fluid rounded-circle" src="/labkes/storage/foto_kegiatan/{{ $val->foto_kegiatan }}">
            </div>
        </div>
    </td>
    <td class="text-center">
        <a href="{{ url('/spr/landingpage/kegiatan/'.$val->id.'/edit') }}"  data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F39F5A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
        <a href="javascript:void()" data-toggle="modal" data-target="#mdlHapusKegiatan" onclick="hapusKegiatan({{ $val->id }})"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C51605" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="10">
        Jumlah Data : {{ $totalrow }}
    </td>
</tr>
<tr>
    <td colspan="10">{{ $kegiatan->appends(['combocariclosing' => Request::get('combocariclosing'), 'pencarianclosing' => Request::get('pencarianclosing')])->render() }}</td>
</tr>

<div class="modal fade" id="mdlHapusKegiatan" tabindex="-1" role="dialog" aria-labelledby="mdlHapusKegiatanTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Kegiatan</h5>
                <button type="button" class="btn-close" data-dismiss="modal"aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="formDeleteKegiatan">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <div class="modal-body">
                    <h5>Apakah anda yakin untuk hapus kegiatan ini?</h5>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" onclick="formDeleteSubmit()">Ya</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript">
     function hapusKegiatan(id)
     {
         var id = id;
         var url = '{{ route("kegiatan.destroy", ":id") }}';
         url = url.replace(':id', id);
         $("#formDeleteKegiatan").attr('action', url);
     }

     function formDeleteSubmit()
     {
         $("#formDeleteKegiatan").submit();
     }

</script>
