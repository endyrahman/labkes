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
                    <th>Laboratorium</th>
                    <th>Jadwal Datang</th>
                    <th>Status Bayar</th>
                    <th class="no-content">#</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $val)
                <tr>
                    <td></td>
                    <td>{{ $val->no_registrasi }}</td>
                    <td>{{ $val->nama_pasien }}</td>
                    <td>
                        @if ($val->jenis_lab_id == 1)
                            Klinik
                        @elseif ($val->jenis_lab_id == 2)
                            Kimia
                        @elseif ($val->jenis_lab_id == 3)
                            Mikrobiologi
                        @endif
                    </td>
                    <td>{{ date('d-m-Y H:i', strtotime($val->tgl_waktu_kunjungan)) }}</td>
                    <td>
                        @if (!$val->status_bayar)
                            <a href="/pembayaran/laboratorium/create/{{$val->id}}" class="btn btn-outline-danger p-1 mb-1">Konfirmasi Bayar</a>
                        @elseif ($val->status_bayar == 1)
                            <span class="shadow-none badge badge-warning">Proses Validasi</span>
                        @elseif ($val->status_bayar == 2)
                            <span class="shadow-none badge badge-success">Lunas</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('/pencetakan/cetakbuktiregistrasi/'.$val->id) }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Bukti Registrasi" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>&nbsp;
<!--                         @if ($val->status_bayar != 2)
                            <a href="/pembayaran/laboratorium/create/{{$val->id}}" class="btn btn-success p-1">Pembayaran</a></td>
                        @endif -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
