@extends('layouts.app')

@section('content')

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Layanan Kami</h5>
        </div>
        <a href="{{ url('/spr/landingpage/layanankami/create') }}" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Layanan Kami</a>
    </div>
    <div class="widget-content widget-content-area">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Layanan</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($layanankami as $val)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td>{{ $val->nama_layanan }}</td>
                        <td style="max-width: 600px">{{ $val->keterangan }}</td>
                        <td>
                            <div class="d-flex">
                                <div class="usr-img-frame mr-2 rounded-circle">
                                    <img alt="avatar" class="img-fluid rounded-circle" src="{{ url('/storage/foto_layanan/'.$val->foto_layanan) }}">
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('/spr/landingpage/layanankami/'.$val->id.'/edit') }}"  data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F39F5A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
