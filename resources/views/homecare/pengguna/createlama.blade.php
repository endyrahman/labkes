@extends('layouts.app')

@section('content')
@php
    ini_set('date.timezone', 'Asia/Jakarta');
@endphp
<style type="text/css">
.flatpickr-calendar.open {
    display: inline-block;
    z-index: 99999!important;
}

#geocoder-container > div {
    min-width: 50%;
    margin-left: 25%;
}

.coordinates {
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    position: absolute;
    bottom: 40px;
    left: 10px;
    padding: 5px 10px;
    margin: 0;
    font-size: 11px;
    line-height: 18px;
    border-radius: 3px;
    display: none;
}
#reset {
    position: absolute;
    bottom: 20px;
    left: 20px;
    z-index: 999;
}

#location {
    position: absolute;
    top: 55px;
    right: 23px;
    z-index: 999;
}

.mapboxgl-popup {
max-width: 200px;
}
</style>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Home Care Labkes Kota Semarang</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="info mt-4">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">
                        <form method="POST" action="/homecare/pengguna/storeHomeCare">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input id="jenis_lab_id" name="jenis_lab_id" class="form-control" value="2" type="hidden">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="jenis_homecare_id">Pilih Pelayanan Home Care</label>
                                            <select class="form-control basic" id="jenis_homecare_id" name="jenis_homecare_id" required>
                                                <option value="" selected="selected">Pilih</option>
                                                <option value="1">Laboratorium Klinik</option>
                                                <option value="2">Laboratorium Kimia dan Mikrobiologi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_waktu_kunjungan">Pilih Tgl & Jam Kunjungan Lokasi</label>
                                            <input id="tgl_waktu_kunjungan" name="tgl_waktu_kunjungan" class="form-control" type="text" style="cursor: pointer;" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="nama_lengkap">Nama Lengkap</label>
                                            <input id="nama_lengkap" name="nama_lengkap" class="form-control" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="no_hp">No. HP</label>
                                            <input id="no_hp" name="no_hp" class="form-control" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="alamat_lengkap">Alamat Lengkap</label>
                                            <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows=3 required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href='#' id='reset' class='btn btn-primary'>reset</a>
                                        <a href='#' id='location' class='btn btn-secondary'>My Location</a>
                                        <div id='map' style='width: 100%; height: 400px;'></div>
                                        <pre id="coordinates" class="coordinates"></pre>
                                    </div>
                                </div>
                                <div class="row pt-4">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="jarak">Jarak Lokasi ( Km )</label>
                                            <input type="text" name="jarak" id='jarak' class="form-control" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="no_hp">Latitude</label>
                                            <input type="text" class="form-control" name="latitude" id='latitude' readonly required>

                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="no_hp">Longitude</label>
                                            <input type="text" class="form-control" name="longitude" id='longitude' readonly required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="simpanHomeCare" class="btn btn-primary btn-block mb-4 mt-4">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlJadwalKunjungan" tabindex="-1" role="dialog" aria-labelledby="mdlJadwalKunjunganTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlJadwalKunjunganTitle">Pilih Waktu Kunjungan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <input id="waktu_kunjungan" name="waktu_kunjungan" value="{{ date('H:i') }}" class="form-control" type="hidden" readonly required>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="tgl_kunjungan">Waktu Kunjungan</label>
                        <input id="tgl_kunjungan" name="tgl_kunjungan" value="{{ date('d-m-Y') }}" class="form-control flatpickr flatpickr-input active" type="text" onchange="waktuKunjungan()" readonly required>
                    </div>
                </div>
                <div class="col-sm-12" id="jadwal">

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                <button type="button" class="btn btn-primary" id="simpanjadwal" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="https://d3js.org/d3-queue.v3.min.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.2/mapbox-gl-geocoder.min.js"></script>
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.2/mapbox-gl-geocoder.css" type="text/css">

<script>
    document.getElementById('reset').onclick = clearMap;

    mapboxgl.accessToken = 'pk.eyJ1IjoiYWFyb25saWRtYW4iLCJhIjoiNTVucTd0TSJ9.wVh5WkYXWJSBgwnScLupiQ';

    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [110.3853251451, -6.9917601582],
        zoom: 13,
        hash: true
    });

    var nav = new mapboxgl.NavigationControl();
    map.addControl(nav, 'top-left');

    var start = [110.3853251451, -6.9917601582];
    var end = [];

    var popupStart = new mapboxgl.Popup({ offset: 25 }).setText(
        'Labkes Kota Semarang'
    );

    new mapboxgl.Marker({ "color": "#b40219" })
        .setLngLat(start)
        .setPopup(popupStart)
        .addTo(map)
        .togglePopup();

    var api = 'https://api.mapbox.com/directions/v5/';
    var profiles = {
        driving: {
            color: '#3bb2d0'
        }
    };

    let geolocate = new mapboxgl.GeolocateControl({
      positionOptions: {
        enableHighAccuracy: true
      },
      trackUserLocation: true
    });

    var geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl,
        marker: null
    });

    // Add the control to the map.
    map.addControl(geocoder);
    map.addControl(geolocate);

    var count = 1;

    $("#location").click(function(){
        geolocate.trigger();

        geolocate.on('geolocate', function(e) {
            map.setZoom(16);

            var loc = [e.coords.longitude, e.coords.latitude];
            var popup = new mapboxgl.Popup({ offset: 25 }).setText(
                'Klik di sini untuk mendapatkan jarak lokasi atau klik pada map untuk menentukan lokasi yang tepat'
            );

            if (count == 1) {
                new mapboxgl.Marker()
                    .setLngLat(loc)
                    .setPopup(popup)
                    .addTo(map)
                    .togglePopup();
            }

            count += 1;
        });
    });

    map.on('load', go);
    map.on('click', go);

    function go(e) {
        if (e.type === 'click') {
            clearMap();
        }

        if (e.type === 'click' && !start) start = [110.3853251451, -6.9917601582];

        if (!map.getSource('start')) {
            map.addSource('start', {
                type: 'geojson',
                data: {
                    type: 'Point',
                    coordinates: [110.3853251451, -6.9917601582]
                }
            });

            map.addLayer({
                'id': 'start',
                'type': 'circle',
                'source': 'start',
                'layout': {},
                'paint': {
                    'circle-radius': 10,
                    'circle-color': profiles.driving.color
                }
            });
        }

        if (e.type === 'click') {
            end = [e.lngLat.lng, e.lngLat.lat];
            if (end.toString() === start.toString()) {
                end = null;
                return
            }
        }

        map.addSource('end', {
            type: 'geojson',
            data: {
                type: 'Point',
                coordinates: [end[0], end[1]]
            }
        });

        map.addLayer({
            'id': 'end',
            'type': 'circle',
            'source': 'end',
            'layout': {},
            'paint': {
                'circle-radius': 10,
                'circle-color': profiles.driving.color
            }
        });

        $('#latitude').val(end[0]);
        $('#longitude').val(end[1]);

        if (start && end) requestProfiles(start, end, Object.keys(profiles));
    }

    function requestProfiles() {
        var queue = d3.queue();

        Object.keys(profiles).forEach(function(profile) {
            queue.defer(route, start, end, profile);
        });

        queue.awaitAll(function(error, results) {
            results.forEach(function(result) {
                if (result && result.profile) {
                    profiles[result.profile].route = result.routes[0];
                }
            })
            draw();
        });

        function route(start, end, profile, cb) {
           var startEnd = encodeURIComponent(start + ';' + end);
            var request = new XMLHttpRequest();
            var url = api + 'mapbox/' + profile + '/' + startEnd + '.json?access_token=pk.eyJ1IjoiYWFyb25saWRtYW4iLCJhIjoiNTVucTd0TSJ9.wVh5WkYXWJSBgwnScLupiQ&geometries=geojson&overview=full';

            request.abort();
            request.open('GET', url, true);
            request.send();

            request.onload = function() {
                if (request.status >= 200 && request.status < 400) {
                    var data = JSON.parse(request.responseText);
                    if (data.error) {
                        console.log('error');
                        return clear();
                    }
                    console.log(data);
                    var km = data.routes[0].distance / 1000;
                    if (km != 0)
                        $('#jarak').val(km.toFixed(2));
                    data.profile = profile;
                    return cb(null, data);
                } else {
                    // never actually error
                    return cb(null, false);
                }
            };

            request.onerror = function() {
              return cb(null, false);
            };
        }
    }

    function draw() {
        // I know

        var bounds = new mapboxgl.LngLatBounds(start, end);

        Object.keys(profiles).forEach(function(profile, idx) {
            map.addSource(profile + ' route', {
                type: 'geojson',
                data: profiles[profile].route.geometry
            });
            var route = {
                'id': profile + ' route',
                'type': 'line',
                'source': profile + ' route',
                'layout': {
                    'line-join': 'round',
                    'line-cap': 'round'
                },
                'paint': {
                    'line-color': profiles[profile].color,
                    'line-width': 4,
                    'line-opacity': 1
                }
            };
            map.addLayer(route, 'start');

            var result = profiles[profile].route.geometry.coordinates.reduce(function(previous, current) {
                return bounds.extend(current);
            });

        });
    }

    function clearMap() {
        Object.keys(profiles).forEach(function(profile) {
            if (map.getLayer(profile + ' route')) map.removeLayer(profile + ' route');
            if (map.getSource(profile + ' route')) map.removeSource(profile + ' route');
        });

        ['start', 'end'].forEach(function(item) {
            if (map.getLayer(item)) map.removeLayer(item);
            if (map.getSource(item)) map.removeSource(item);
        });

        start = [110.3853251451, -6.9917601582];
        end = null;
        $('#jarak').val('');
        $('#latitude').val('');
        $('#longitude').val('');
    }

    $('#tgl_waktu_kunjungan').on('click', function(e) {
        var token = $("input[name='_token']").val();
        var jenis_pemeriksaan_id = $(this).val();
        $('#mdlJadwalKunjungan').modal('toggle');
        waktuKunjungan();
    });

    function waktuKunjungan() {
        var waktu = $("#waktu_kunjungan").val();
        var tgl = $("#tgl_kunjungan").val();
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/getJadwalPemeriksaan') }}",
            data: { _token:token, waktu:waktu, tgl:tgl },
            success:function(data){
                $('#jadwal').html(data);
            }
        });
    }

    $("#simpanHomeCare").click(function(){
        var lat = $('#latitude').val();

        if (lat === null) {
            alert('Pilih Lokasi anda terlebih dahulu sebelum menyimpan data');
        }
    });
</script>
@endsection
