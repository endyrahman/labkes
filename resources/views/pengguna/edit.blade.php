@extends('layouts.app')

@section('content')
@php
    ini_set('date.timezone', 'Asia/Jakarta');
@endphp
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
        <div class="widget-heading">
            <div class="">
                <h5 class=""> Edit Pengguna</h5>
            </div>
        </div>
        <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
            <div class="info mt-4">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">
                                <form method="POST" action="/pengguna/updatepengguna">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="user_id" value="{{ $user->id }}" />
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input id="email" name="email" class="form-control" type="email" value="{{ $user->email }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="no_hp">No. Hp</label>
                                                <input id="no_hp" name="no_hp" class="form-control" type="text" value="{{ $user->no_hp }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="password">Password Baru</label>
                                                <input id="password" name="password" class="form-control" type="password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2 mb-4 mr-2">Simpan Data</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script>
    var f2 = flatpickr(document.getElementById('tgl_lahir'), {
        enableTime: true,
        dateFormat: "d-m-Y"
    });
</script>
@endsection
