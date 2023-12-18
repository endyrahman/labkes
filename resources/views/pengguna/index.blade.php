@extends('layouts.app')

@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
        <div class="widget-heading">
            <div class="">
                <h5 class=""> Pengguna</h5>
            </div>
        </div>
        <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
            <table id="dt-pengguna" class="table dt-table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Hp</th>
                        <th class="no-content">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $val)
                    <tr>
                        <td></td>
                        <td>{{ $val->nama_lengkap }}</td>
                        <td>{{ $val->email }}</td>
                        <td>{{ $val->no_hp }}</td>
                        <td><a href="{{ url('/pengguna/edit/'.$val->id) }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Data" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
