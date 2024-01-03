@extends('layouts.app')

@section('content')
<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Master Data Jenis Pemeriksaaan</h5>
        </div>
        <a href="{{ url('/master/jenispemeriksaan/create') }}" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Tambah</a>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <table id="dt-mstjenispemeriksaan" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Laboratorium</th>
                    <th>Parameter Pemeriksaan</th>
                    <th style="max-width:16%;">Harga</th>
                    <th>Status</th>
                    <th class="no-content">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $val)
                <tr>
                    <td></td>
                    <td>{{ $val->nama_jenis_lab }}</td>
                    <td>{{ $val->nama_pemeriksaan }}</td>
                    <td style="max-width:16%;">
                        {{ $val->arr_nama_parameter }}
                    </td>
                    <td>
                        @if ($val->status == 1)
                            <span class="shadow-none badge badge-info">Aktif</span>
                        @else
                            <span class="shadow-none badge badge-danger">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('/master/jenispemeriksaan/edit/'.$val->jenis_pemeriksaan_id) }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Data" target="self"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
