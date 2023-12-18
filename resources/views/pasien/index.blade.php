@extends('layouts.app')

@section('content')

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
        <div class="widget-heading">
            <div class="">
                <h5 class=""> Daftar Pasien</h5>
            </div>
            <a href="/pasien/create" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Pasien</a>
        </div>
        <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
            <div class="input-group mb-4">
                <div class="input-group-prepend">
                    <select class="form-control col-md-6" id="combocari" onchange="paginationGrid(1)">
                        <option value="">Pilih Pencarian</option>
                        <option value="nik">NIK</option>
                        <option value="nama_pasien">Nama Pasien</option>
                    </select>
                    <input type="text" class="form-control col-md-8" id="pencarian" onchange="paginationGrid(1)" onkeyup="paginationGrid(1)" placeholder="Masukkan Pencarian">
                </div>
                <button class="btn btn-danger" type="button" id="hapusPencarian" onclick="hapusPencarian(1)" style="display:none;">Hapus</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-4">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Tgl. Lahir</th>
                            <th class="no-content">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('pasien.list')
                    </tbody>
                </table>
                <input type="hidden" name="hidden_page_gridkimia" id="hidden_page_gridkimia" value="1"/>
            </div>
        </div>
    </div>
</div>
@endsection
