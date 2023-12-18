@extends('layouts.app')

@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Registrasi Online Labkes</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <table id="dt-registrasi-verifikasi" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Registrasi</th>
                    <th>Nama</th>
                    <th>Jadwal Datang</th>
                    <th>Jenis Lab</th>
                    <th>Sampel</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Tgl. Registrasi</th>
                    <th class="no-content">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $val)
                <tr>
                    <td></td>
                    <td>{{ $val->no_registrasi }}</td>
                    <td>{{ $val->nama_pasien }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($val->tgl_waktu_kunjungan)) }}</td>
                    <td>
                        @if ($val->jenis_lab_id == 1)
                            Klinik
                        @elseif ($val->jenis_lab_id == 2)
                            Kimia
                        @elseif ($val->jenis_lab_id == 3)
                            Mikrobiologi
                        @endif
                    </td>
                    <td>
                        @php
                        $resname = '';
                        foreach (preg_split('#[^a-z]+#i', $val->nama_sampel, -1, PREG_SPLIT_NO_EMPTY) as $word) {
                            $resname .= $word[0];
                        }
                        @endphp
                        {{ $val->nama_kemasan }}
                    </td>
                    <td>{{ $val->lokasi_sampel }}</td>
                    <td>
                        @if ($val->status_bayar == 1)
                            <span class="shadow-none badge badge-info">Belum Bayar</span>
                        @elseif ($val->status_bayar == 2)
                            <span class="shadow-none badge badge-warning">Proses Validasi</span>
                        @elseif ($val->status_bayar == 4)
                            <span class="shadow-none badge badge-success">Lunas</span>
                        @endif
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                            </a>
                            <div class="dropdown-menu bs-tooltip" aria-labelledby="dropdownMenuLink2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Update Status Registrasi">
                                <a class="dropdown-item" href="javascript:void(0);" onclick="mdlDiverifikasi({{$val->pemeriksaan_id}})">Diverifikasi</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="mdlProsesLab({{$val->pemeriksaan_id}})">Proses Lab</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="mdlUploadHasilLab({{$val->pemeriksaan_id}})">Lab Selesai</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="mdlBatalkanRegistrasi({{$val->pemeriksaan_id}})">Dibatalkan</a>
                            </div>
                        </div>
                    </td>
                    <td>{{ date('Y-m-d H:i:s', strtotime($val->created_at)) }}</td>
                    <td><a href="{{ url('/pencetakan/cetakbuktiregistrasi/'.$val->pemeriksaan_id) }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Bukti Registrasi" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>

<div class="modal fade" id="mdlUploadFormLab" tabindex="-1" role="dialog" aria-labelledby="mdlUploadFormLabTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlUploadFormLabTitle">Upload </h5>
            </div>

            <form method="POST" action="/registrasi/verifikasi/updateStatusLab" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="row">
                    <input id="lab_pemeriksaan_id" name="lab_pemeriksaan_id" class="form-control" type="hidden" placeholder="" required>
                    <input id="status_lab" name="status_lab" class="form-control" type="hidden" value="3" placeholder="" required>
                    <div class="col-sm-12">
                        <div class="custom-file-container" data-upload-id="formLab">
                            <label>Upload Form Laboratorium <span style="color:red;">*</span> <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                            <label class="custom-file-container__custom-file" >
                                <input type="file" class="custom-file-container__custom-file__custom-file-input" accept="image/*, application/pdf" id="fileLabPemeriksaan" name="fileLabPemeriksaan" required>
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
                <button type="submit" class="btn btn-primary" id="simpanformlab">Save</button>
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

            <form method="POST" action="/registrasi/verifikasi/updateStatusLab">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="row">
                    <input id="proses_pemeriksaan_id" name="proses_pemeriksaan_id" class="form-control" type="hidden" placeholder="" required>
                    <input id="status_lab" name="status_lab" class="form-control" type="hidden" value="3" placeholder="" required>
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
                        <input type="text" class="form-control" name="no_sampel_silkes" id="no_sampel_silkes" placeholder="No. Sampel Silkes">
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
            <form method="POST" action="/registrasi/verifikasi/updateStatusLab" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="row">
                    <input id="hasil_lab_pemeriksaan_id" name="hasil_lab_pemeriksaan_id" class="form-control" type="hidden" placeholder="" required>
                    <input id="status_hasil" name="status_hasil" class="form-control" type="hidden" value="4" placeholder="" required>
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

<div class="modal fade" id="mdlDiverifikasi" tabindex="-1" role="dialog" aria-labelledby="mdlDiverifikasiTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Registrasi</h5>
                <button type="button" class="btn-close" data-dismiss="modal"aria-label="Close"></button>
            </div>
            <form action="{{ url('/registrasi/verifikasi/setuju') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="pemeriksaan_id" id="pemeriksaan_id" class="form-control"></input>
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

<div class="modal fade" id="mdlBatalkanRegistrasi" tabindex="-1" role="dialog" aria-labelledby="mdlBatalkanRegistrasiTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batalkan Registrasi</h5>
                <button type="button" class="btn-close" data-dismiss="modal"aria-label="Close"></button>
            </div>
            <form action="{{ url('/registrasi/verifikasi/batal') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="batal_pemeriksaan_id" id="batal_pemeriksaan_id" class="form-control"></input>
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
<script src="{{ asset('backend/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('backend/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
<script>

    function mdlBatalkanRegistrasi(id) {
        $('#mdlBatalkanRegistrasi').modal('toggle');
        $('#batal_pemeriksaan_id').val(id);
    }

    function mdlDiverifikasi(id, lab_id) {
        $('#mdlDiverifikasi').modal('toggle');
        $('#pemeriksaan_id').val(id);
    }

    function mdlUploadFormLab(id) {
        $('#mdlUploadFormLab').modal('toggle');
        $('#lab_pemeriksaan_id').val(id);
    }

    function mdlProsesLab(id) {
        $('#mdlProsesLab').modal('toggle');
        $('#proses_pemeriksaan_id').val(id);
    }

    function mdlUploadHasilLab(id) {
        $('#mdlUploadHasilLab').modal('toggle');
        $('#hasil_lab_pemeriksaan_id').val(id);
    }

    var upformLab = new FileUploadWithPreview('formLab');
    var upformHasilLab = new FileUploadWithPreview('formHasilLab');

</script>
@endsection
