@extends('layouts.app')

@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Registrasi Homecare Labkes</h5>
        </div>
        <a href="{{ url('/homecare/create') }}" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Registrasi</a>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <table id="dt-homecare-pengguna" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Tgl. Waktu Kunjungan</th>
                    <th>Status</th>
                    <th style="display:none;">OrderByTgl</th>
                    <th style="display:none;">OrderByStatus</th>
                    <th class="no-content">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $val)
                    <tr>
                        <td></td>
                        <td>{{ $val->nama_lengkap }}</td>
                        <td>{{ $val->no_hp }}</td>
                        <td>{{ $val->alamat_lengkap }}</td>
                        <td>{{ date('d-m-Y H:i:s', strtotime($val->tgl_waktu_kunjungan)) }}</td>
                        <td>
                        @if ($val->status_homecare_id == 1)
                            <span class="shadow-none badge badge-info">{{ $val->status_homecare }}</span>
                        @elseif ($val->status_homecare_id == 2)
                            <span class="shadow-none badge badge-success">{{ $val->status_homecare }}</span>
                        @else
                            <span class="shadow-none badge badge-danger">{{ $val->status_homecare }}</span>
                        @endif
                        </td>
                        <td style="display:none;">{{ $val->tgl_waktu_kunjungan }}</td>
                        <td style="display:none;">{{ $val->status_homecare_id }}</td>
                        <td>
                            @if ($val->status_homecare_id == 1)
                                <button type="button" class="btn btn-danger btn-sm bs-tooltip p-1" onclick="hapusRegistrasi({{ $val->homecare_id }})">Hapus</button>&nbsp;&nbsp;
                            @endif
                            @if ($val->status_homecare_id != 3)
                                <a href="{{ url('/homecare/edit/'.$val->homecare_id) }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Data" target="self"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="mdlHapusRegistrasi" tabindex="-1" role="dialog" aria-labelledby="mdlHapusRegistrasiTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Registrasi</h5>
                <button type="button" class="btn-close" data-dismiss="modal"aria-label="Close"></button>
            </div>
            <form action="{{ url('/homecare/delete') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="homecare_id" id="homecare_id" class="form-control"></input>
                    <h5>Apakah anda yakin untuk hapus registrasi ini?</h5>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Ya</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript">
    function hapusRegistrasi(id){
        $('#mdlHapusRegistrasi').modal('toggle');
        $('#homecare_id').val(id);
    }
</script>
@endsection
