@extends('layouts.app')

@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Registrasi Homecare Labkes</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <table id="dt-homecare-admin" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Tgl. Waktu Kunjungan</th>
                    <th>Status</th>
                    <th style="display:none;">Tgl. Input</th>
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
                            <span class="shadow-none badge badge-primary">{{ $val->status_homecare }}</span>
                        @elseif ($val->status_homecare_id == 3)
                            <span class="shadow-none badge badge-warning">{{ $val->status_homecare }}</span>
                        @elseif ($val->status_homecare_id == 4)
                            <span class="shadow-none badge badge-success">{{ $val->status_homecare }}</span>
                        @else
                            <span class="shadow-none badge badge-danger">{{ $val->status_homecare }}</span>
                        @endif
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                </a>
                                <div class="dropdown-menu bs-tooltip" aria-labelledby="dropdownMenuLink2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Update Status Registrasi">
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="mdlDiverifikasi({{$val->homecare_id}})">Verifikasi Kunjungan</a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="mdlProsesLab({{$val->homecare_id}})">Proses Lab</a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="mdlUploadHasilLab({{$val->homecare_id}})">Lab Selesai</a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="mdlBatalkanRegistrasi({{$val->homecare_id}})">Dibatalkan</a>
                                </div>
                            </div>
                        </td>
                        <td style="display:none;">{{ $val->tgl_input }}</td>
                        <td>
                            @if ($val->status_homecare_id == 1)
                                <button type="button" class="btn btn-danger btn-sm bs-tooltip p-1" onclick="hapusRegistrasi({{ $val->homecare_id }})">Hapus</button>&nbsp;&nbsp;
                            @endif
                            @if ($val->status_homecare_id != 3 and Auth::user()->role_id != 1)
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

<div class="modal fade" id="mdlDiverifikasi" tabindex="-1" role="dialog" aria-labelledby="mdlDiverifikasiTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Kunjungan Homecare</h5>
                <button type="button" class="btn-close" data-dismiss="modal"aria-label="Close"></button>
            </div>
            <form action="{{ url('/homecare/verifikasi/setuju') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="homecare_setuju_id" id="homecare_setuju_id" class="form-control"></input>
                    <h5>Apakah anda yakin untuk memverifikasi registrasi ini?</h5>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Ya</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlProsesLab" tabindex="-1" role="dialog" aria-labelledby="mdlProsesLabTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlUploadFormLabTitle">Proses Lab Penerimaan Sampel </h5>
            </div>

            <form method="POST" action="{{ url('/homecare/verifikasi/updateStatusLab') }}">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="row">
                    <input id="homecare_proseslab_id" name="homecare_proseslab_id" class="form-control" type="hidden" placeholder="" required>
                </div>
                <div class="row pb-2">
                    <div class="col-sm-4">
                        <label class="form-label">No. Pelanggan</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="no_pelanggan_silkes" id="no_pelanggan_silkes" placeholder="No. Pelanggan Silkes">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label class="form-label">No. Sampel</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="no_sample_silkes" id="no_sample_silkes" placeholder="No. Sampel Silkes">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                <button type="submit" class="btn btn-primary" id="simpanformlab">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlUploadHasilLab" tabindex="-1" role="dialog" aria-labelledby="mdlJadwalKunjunganTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlJadwalKunjunganTitle">Upload </h5>
            </div>
            <form method="POST" action="{{ url('/homecare/verifikasi/updateStatusSelesaiLab') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="row">
                    <input id="homecare_hasillab_id" name="homecare_hasillab_id" class="form-control" type="hidden" placeholder="" required>
                    <div class="col-sm-12">
                        <div class="custom-file-container" data-upload-id="formHasilLab">
                            <label>Upload Hasil Laboratorium <span style="color:red;">*</span> <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                            <label class="custom-file-container__custom-file" >
                                <input type="file" class="custom-file-container__custom-file__custom-file-input" accept="image/*, application/pdf" id="fileLabHasilPemeriksaan" name="fileLabHasilPemeriksaan" required>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                            </label>
                            <div class="custom-file-container__image-preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                <button type="submit" class="btn btn-primary" id="simpanhasillab">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlBatalkanRegistrasi" tabindex="-1" role="dialog" aria-labelledby="mdlBatalkanRegistrasiTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batalkan Registrasi</h5>
                <button type="button" class="btn-close" data-dismiss="modal"aria-label="Close"></button>
            </div>
            <form action="{{ url('/homecare/verifikasi/batal') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="homecare_batal_id" id="homecare_batal_id" class="form-control"></input>
                    <h5>Apakah anda yakin untuk membatalkan registrasi ini?</h5>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Keterangan Batal</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                        </div>
                    </div>
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
<script src="{{ asset('backend/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
<script type="text/javascript">
    function mdlDiverifikasi(id) {
        $('#mdlDiverifikasi').modal('toggle');
        $('#homecare_setuju_id').val(id);
    }

    function mdlProsesLab(id) {
        $('#mdlProsesLab').modal('toggle');
        $('#homecare_proseslab_id').val(id);
    }

    function mdlUploadHasilLab(id) {
        $('#mdlUploadHasilLab').modal('toggle');
        $('#homecare_hasillab_id').val(id);
    }

    function hapusRegistrasi(id){
        $('#mdlHapusRegistrasi').modal('toggle');
        $('#homecare_id').val(id);
    }

    function mdlBatalkanRegistrasi(id) {
        $('#mdlBatalkanRegistrasi').modal('toggle');
        $('#batal_pemeriksaan_id').val(id);
    }

    var upformHasilLab = new FileUploadWithPreview('formHasilLab');
</script>
@endsection
