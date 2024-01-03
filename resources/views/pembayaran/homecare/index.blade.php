@extends('layouts.app')

@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Pembayaran</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <table id="dt-registrasi-sampel" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Registrasi</th>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>Alamat Lengkap</th>
                    <th>Jadwal Datang</th>
                    <th>Status Lab</th>
                    <th>Status Bayar</th>
                    <th class="no-content">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $val)
                <tr>
                    <td></td>
                    <td>{{ $val->no_registrasi }}</td>
                    <td>{{ $val->nama_lengkap }}</td>
                    <td>{{ $val->no_hp }}</td>
                    <td>{{ $val->alamat_lengkap }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($val->tgl_waktu_kunjungan)) }}</td>
                    <td>
                        @if ($val->status_homecare_id == 2)
                            <span class="shadow-none badge badge-warning">Diverifikasi</span>
                        @elseif ($val->status_homecare_id == 3)
                            <span class="shadow-none badge badge-primary">Proses Lab</span>
                        @elseif ($val->status_homecare_id == 4)
                            <span class="shadow-none badge badge-success">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if ($val->status_bayar == 1)
                            <span class="shadow-none badge badge-primary">{{ $val->nama_status_bayar }}</span>
                        @elseif ($val->status_bayar == 2)
                            <span class="shadow-none badge badge-success">{{ $val->nama_status_bayar }}</span>
                        @else
                            <span class="shadow-none badge badge-danger">Belum Bayar</span>
                        @endif

                    </td>
                    <td>
                        @if ($val->status_bayar != 2)
                            <a href="{{ url('/pembayaran/homecare/create/'.$val->homecare_id) }}" class="btn btn-success p-1">Pembayaran</a></td>
                        @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
