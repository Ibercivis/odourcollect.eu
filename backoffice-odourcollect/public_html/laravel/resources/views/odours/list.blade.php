@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

    <style>
        .menu {
            position: absolute;
            z-index: 2;
            right: 25px;
            top: -8px;
        }

        .marker-cluster div {background-color: rgb(0, 177, 135)!important;}

        .marker-cluster {background-color: rgba(0, 177, 135, 0.48)!important;}

        .leaflet-control-attribution{display: none!important;}

        .leaflet-popup{
            bottom: -36px!important;
            left: -43px!important;
        }

        .center{
            text-align:center;
        }
    </style>

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <div class="row">
                @if($errors->any())
                    @if($errors->first() == 'success')
                        <div class="card-body border-top">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Done!</strong> It has been verified correctly
                                <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                            </div>
                        </div>
                    @elseif ($errors->first() == 'attach')
                        <div class="card-body border-top">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Done!</strong> It has been attached correctly
                                <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                            </div>
                        </div>
                    @elseif ($errors->first() == 'attach_fail')
                        <div class="card-body border-top">
                            <div class="alert alert-dismissible alert-dismissible fade show" role="alert">
                                <strong>Ops!</strong> An error occurred. Please, try it later
                                <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif


                <div class="col-md-12">
                    <div style="padding: 10px 30px 20px 30px">
                        @if(Auth::guard('web')->check())
                         <a href="{{ route('odour.attach') }}"  class="btn btn-primary">Attach observations to case studies</a>
                        @else
                        <a href="{{ route('admin.odour.attach') }}"  class="btn btn-primary">Attach observations to case studies</a>
                        @endif
                        
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="menu">
                                        <form id="maps_radio">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Case study filter
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item">
                                                        <label class="custom-control custom-radio custom-control">
                                                            <input type="radio" name="radio-map" checked class="custom-control-input" value="0"><span class="custom-control-label">None</span>
                                                        </label>
                                                    </a>
                                                    @foreach($maps as $map)
                                                        <a class="dropdown-item">
                                                            <label class="custom-control custom-radio custom-control">
                                                                <input type="radio" name="radio-map" class="custom-control-input" value="{{$map->id}}"><span class="custom-control-label">{{$map->name}}</span>
                                                            </label>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div id='map' style='width: 100%; height: 500px; z-index: 1'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid dashboard-content">
                    <div class="export">
                        @if(Auth::guard('web')->check())
                        {!! Form::open(['route' => array('odour.download'), 'method' => 'GET', 'id' => 'download', 'name' => 'download', 'class' => 'navbar-form navbar-left']) !!}
                            {!! Form::hidden('type_down', '', ['id' => 'type_down']) !!}
                            {!! Form::hidden('subtype_down', '', ['id' => 'subtype_down']) !!}
                            {!! Form::hidden('status_down', '', ['id' => 'status_down']) !!}
                            {!! Form::hidden('intensity_down', '', ['id' => 'intensity_down']) !!}
                            {!! Form::hidden('annoy_down', '', ['id' => 'annoy_down']) !!}
                            {!! Form::hidden('map_down', '', ['id' => 'map_down']) !!}
                            {!! Form::hidden('publish_date_src_down', '', ['id' => 'publish_date_src_down']) !!}
                            {!! Form::hidden('publish_date_dst_down', '', ['id' => 'publish_date_dst_down']) !!}
                            <div style="width: 90%;">
                                <input style="margin-left: 88%;" id="check" type="submit" class="btn btn-primary" value="Download xlsx">
                            </div>
                        {!! Form::close() !!}
                        @else
                        {!! Form::open(['route' => array('odour.admin.download'), 'method' => 'GET', 'id' => 'download', 'name' => 'download', 'class' => 'navbar-form navbar-left']) !!}
                            {!! Form::hidden('type_down', '', ['id' => 'type_down']) !!}
                            {!! Form::hidden('subtype_down', '', ['id' => 'subtype_down']) !!}
                            {!! Form::hidden('status_down', '', ['id' => 'status_down']) !!}
                            {!! Form::hidden('intensity_down', '', ['id' => 'intensity_down']) !!}
                            {!! Form::hidden('annoy_down', '', ['id' => 'annoy_down']) !!}
                            {!! Form::hidden('map_down', '', ['id' => 'map_down']) !!}
                            {!! Form::hidden('publish_date_src_down', '', ['id' => 'publish_date_src_down']) !!}
                            {!! Form::hidden('publish_date_dst_down', '', ['id' => 'publish_date_dst_down']) !!}
                            <div style="width: 90%;">
                                <input style="margin-left: 88%;" id="check" type="submit" class="btn btn-primary" value="Download xlsx">
                            </div>
                        {!! Form::close() !!}
                        @endif
                    </div>

                    <div class="row no_margin selects">
                         @if(Auth::guard('web')->check())
                        {!! Form::model(Request::all(), ['route' => array('odour.list'), 'method' => 'GET', 'id' => 'list_form', 'class' => 'navbar-form navbar-left']) !!}
                            <div class="form-group">
                                {!! Form::select('type', [null => 'Type'] + $types->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'type']) !!}
                                @if(!empty($subtypes))
                                {!! Form::select('subtype', [null => 'Subtype'] + $subtypes->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'subtype']) !!}
                                @endif
                                {!! Form::select('status', array('' => 'Status', '1' => 'Published', '0' => 'Unverified', '2' => 'Deleted'), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'status']) !!}

                                {!! Form::select('intensity', [null => 'Intensity'] + $intensities->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'intensity']) !!}

                                {!! Form::select('annoy', [null => 'Nice/foul'] + $annoys->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'annoy']) !!}

                                {!! Form::select('map', [null => 'Case study'] + $maps->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'map']) !!}
                            </div>
                            

                            <div class="form-group">
                                <label for="program_type_select">Published dates (dd/mm/aaaa)</label>
                                <span class="between-dates">From  </span>{!! Form::input('date', 'publish_date_src', "", ['class' => 'form-control', 'id' => 'publish_date_src_select', 'onchange' => 'this.form.submit()', 'data-date-format' => "yyyy-mm-dd"]) !!}
                                <span class="between-dates">To  </span>{!! Form::input('date', 'publish_date_dst', "", ['class' => 'form-control', 'id' => 'publish_date_dst_select', 'onchange' => 'this.form.submit()', 'data-date-format' => "yyyy-mm-dd"]) !!}
                            </div>
                        {!! Form::close() !!}
                        @else
                        {!! Form::model(Request::all(), ['route' => array('admin.odour.list'), 'method' => 'GET', 'id' => 'list_form', 'class' => 'navbar-form navbar-left']) !!}
                            <div class="form-group">
                                {!! Form::select('type', [null => 'Type'] + $types->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'type']) !!}
                                @if(!empty($subtypes))
                                {!! Form::select('subtype', [null => 'Subtype'] + $subtypes->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'subtype']) !!}
                                @endif
                                {!! Form::select('status', array('' => 'Status', '1' => 'Published', '0' => 'Unverified', '2' => 'Deleted'), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'status']) !!}

                                {!! Form::select('intensity', [null => 'Intensity'] + $intensities->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'intensity']) !!}

                                {!! Form::select('annoy', [null => 'Nice/foul'] + $annoys->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'annoy']) !!}

                                {!! Form::select('map', [null => 'Case study'] + $maps->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'map']) !!}
                            </div>

                            <div class="form-group">
                                <label for="program_type_select">Published dates (dd/mm/aaaa)</label>
                                <span class="between-dates">From  </span>{!! Form::input('date', 'publish_date_src', "", ['class' => 'form-control', 'id' => 'publish_date_src_select', 'onchange' => 'this.form.submit()', 'data-date-format' => "yyyy-mm-dd"]) !!}
                                <span class="between-dates">To  </span>{!! Form::input('date', 'publish_date_dst', "", ['class' => 'form-control', 'id' => 'publish_date_dst_select', 'onchange' => 'this.form.submit()', 'data-date-format' => "yyyy-mm-dd"]) !!}
                            </div>
                        {!! Form::close() !!}
                        @endif
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Observations list</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" data-page-length='10' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Observation Type</th>
                                            <th scope="col">Observation Subtype</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">User ID</th>
                                            <th scope="col" class="center">Verified</th>
                                            <th scope="col">Published at</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($odours as $odour)
                                      
                                            <tr>
                                                <th scope="row">{{$odour->id_odor}}</th>
                                                <td>{{$odour->odour_type_father}}</td>
                                                <td>{{$odour->odour_type}}</td>
                                                <td>{{$odour->address}}</td>
                                                <td>{{$odour->id_user}}</td>
                                                <!-- Aqui -->
                                                @if ($odour->verified == 1)
                                                <!-- <td>Yes</td> -->
                                                    <td>
                                                        {!! Form::open(['route' => 'odour.update.status', 'method' => 'POST', 'id' => 'updateOdourStatus', 'class' => '']) !!}
                                                            <input type="hidden" id="odour_id" name="odour_id" value="{{$odour->id_odor}}">
                                                            <input type="hidden" id="status" value="off"  name="status">
                                                            <input type="submit" class="btn-primary" value="Unverify" style="margin:0px">
                                                        {!! Form::close() !!}
                                                @else
                                                
                                                @if(Auth::guard('web')->check())
                                                <td><a class="btn-primary" href="{{ route('odour.verify', ['id' => $odour->id_odor])}}">Verify</a></td>
                                                @else
                                                <td><a class="btn-primary" href="{{ route('odour.verify', ['id' => $odour->id_odor])}}">Verify</a></td>
                                                @endif
                                                
                                                @endif
                                                <td>{{$odour->published_at}}</td>
                                                @if(Auth::guard('web')->check())
                                                
                                               <td><a class="btn-primary" href="{{ route('odour.show', ['id' => $odour->id_odor])}}">VIEW</a></td>
                                               @else
                                                <td><a class="btn-primary" href="{{ url('admin/odour', ['id' => $odour->id_odor])}}">VIEW</a></td>
                                                @endif
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
    @if(Auth::guard('web')->check())
    <script>
        var markers = [];
        var markers_layout = '';

        $(document).on('ready', function() {
            $('#table').DataTable({
                ordering: false,
                bInfo : false
            });

            chargeMap();

            if(getUrlParameter('publish_date_src')){ $('#publish_date_src_select').val(getUrlParameter('publish_date_src')); }

            if(getUrlParameter('publish_date_dst')){ $('#publish_date_dst_select').val(getUrlParameter('publish_date_dst')); }

            $('#maps_radio').change(function(){
                markers.forEach(function(m){
                    m.remove();
                });
                markers_layout.clearLayers();
                chargeMap();
            });

            $('#check').on("click", function(event) {
                event.preventDefault();

                $('input#status_down').attr('value', $('#status').val());
                $('input#type_down').attr('value', $('#type').val());
                $('input#subtype_down').attr('value', $('#subtype').val());
                $('input#permission_down').attr('value', $('#permission').val());
                $('input#map_down').attr('value', $('select[name=map] option:selected').val());
                $('input#register_date_src_down').attr('value', $('#register_date_src_select').val());
                $('input#register_date_dst_down').attr('value', $('#register_date_dst_select').val());
                $('input#publish_date_src_down').attr('value', $('#publish_date_src_select').val());
                $('input#publish_date_dst_down').attr('value', $('#publish_date_dst_select').val());

                $("#download").submit();
            });
        });

        var map = L.map( 'map', {
            center: [41.385063, 2.173404],
            minZoom: 2,
            maxZoom: 18,
            zoom: 5,
            zoomControl:false
        });

        L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2xpbmdzaG90MDgiLCJhIjoiY2puMnZzZm02MXN0dzNrcGw1em9yZmZjdCJ9.En40T_Cg1OtKJVpulVLxNg').addTo(map);

        function chargeMap(){
            selected_value = $("input[name='radio-map']:checked").val();
            $.ajax({
                url: '{{ route('home') }}' + '/odour/zone/' + selected_value + '/markers/' ,
                type: "GET",
                beforeSend : function() {},
                success: function(response) {

                    markers_layout = L.markerClusterGroup({
                        removeOutsideVisibleBounds: true,
                        disableClusteringAtZoom: 19,
                        maxClusterRadius: 30,
                        showCoverageOnHover: false,
                    });

                    OdourIcon = L.Icon.extend({
                        options: {
                            iconSize:     [33, 39],
                            iconAnchor:   [16, 40],
                            popupAnchor:  [-3, -76]
                        }
                    });
                    spot0 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot0.png') }}'});
                    spot1 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot1.png') }}'});
                    spot2 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot2.png') }}'});
                    spot3 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot3.png') }}'});
                    spot4 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot4.png') }}'});
                    spot5 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot5.png') }}'});
                    spot6 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot6.png') }}'});
                    spot7 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot7.png') }}'});
                    spot8 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot7.png') }}'});
                    spot9 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot0.png') }}'});

                    for($i=0; $i < response.length; $i++){

                        if (response[$i].verified === 1) {
                            if((response[$i].lat != undefined) && (response[$i].lng != undefined)){
                            if(response[$i].id_odor_type === 9){

                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot9}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                            } else {

                            switch (response[$i].color) {
                                case 1:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot1}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 2:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot2}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 3:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot3}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 4:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot4}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 5:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot5}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 6:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot6}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 7:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot7}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;

                                case 8:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot8}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;

                                case 9:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot9}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;

                            }
                            }
                        } else {
                                    break;
                                }
                        } else {
                            marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot0}).addTo(markers_layout).bindPopup('<a style="margin-left:3px" class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a><p style="margin-top: 24px;"><a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id +'/verify">Verify</a></p>');
                            marker.id = response[$i].id;
                        }

                    }
                    map.addLayer(markers_layout);
                },
                error: function (data, textStatus, errorThrown) {
                    console.log(data);
                },
            });
        }

        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] === sParam) { return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]); }
            }
        }
    </script>
    @else
    <script>
        var markers = [];
        var markers_layout = '';

        $(document).on('ready', function() {
            $('#table').DataTable({
                ordering: false,
                bInfo : false
            });

            chargeMap();

            if(getUrlParameter('publish_date_src')){ $('#publish_date_src_select').val(getUrlParameter('publish_date_src')); }

            if(getUrlParameter('publish_date_dst')){ $('#publish_date_dst_select').val(getUrlParameter('publish_date_dst')); }

            $('#maps_radio').change(function(){
                markers.forEach(function(m){
                    m.remove();
                });
                markers_layout.clearLayers();
                chargeMap();
            });

            $('#check').on("click", function(event) {
                event.preventDefault();
                $('input#type_down').attr('value', $('#type').val());
                $('input#subtype_down').attr('value', $('#subtype').val());
                $('input#status_down').attr('value', $('#status').val());
                $('input#permission_down').attr('value', $('#permission').val());
                $('input#map_down').attr('value', $('select[name=map] option:selected').val());
                $('input#register_date_src_down').attr('value', $('#register_date_src_select').val());
                $('input#register_date_dst_down').attr('value', $('#register_date_dst_select').val());
                $('input#publish_date_src_down').attr('value', $('#publish_date_src_select').val());
                $('input#publish_date_dst_down').attr('value', $('#publish_date_dst_select').val());

                $("#download").submit();
            });
        });

        var map = L.map( 'map', {
            center: [41.385063, 2.173404],
            minZoom: 2,
            maxZoom: 18,
            zoom: 10,
            zoomControl:false
        });

        L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2xpbmdzaG90MDgiLCJhIjoiY2puMnZzZm02MXN0dzNrcGw1em9yZmZjdCJ9.En40T_Cg1OtKJVpulVLxNg').addTo(map);

        function chargeMap(){
            selected_value = $("input[name='radio-map']:checked").val();

            $.ajax({
                url: '{{ route('home') }}' + '/odour/zone/' + selected_value + '/markers/' ,
                type: "GET",
                beforeSend : function() {},
                success: function(response) {

                    markers_layout = L.markerClusterGroup({
                        removeOutsideVisibleBounds: true,
                        disableClusteringAtZoom: 19,
                        maxClusterRadius: 30,
                        showCoverageOnHover: false,
                    });

                    OdourIcon = L.Icon.extend({
                        options: {
                            iconSize:     [33, 39],
                            iconAnchor:   [16, 40],
                            popupAnchor:  [-3, -76]
                        }
                    });
                    spot0 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot0.png') }}'});
                    spot1 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot1.png') }}'});
                    spot2 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot2.png') }}'});
                    spot3 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot3.png') }}'});
                    spot4 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot4.png') }}'});
                    spot5 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot5.png') }}'});
                    spot6 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot6.png') }}'});
                    spot7 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot7.png') }}'});
                    spot8 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot7.png') }}'});
                    spot9 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot0.png') }}'});

                    for($i=0; $i < response.length; $i++){

                        if (response[$i].verified === 1) {
                            if((response[$i].lat != undefined) && (response[$i].lng != undefined)){
                            if(response[$i].id_odor_type === 9){

                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot9}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                            } else {
                            switch (response[$i].color) {
                                case 1:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot1}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/admin/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 2:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot2}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/admin/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 3:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot3}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/admin/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 4:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot4}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/admin/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 5:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot5}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/admin/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 6:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot6}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/admin/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;
                                case 7:
                                    marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot7}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/admin/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                                    break;

                            }
                        }
                        } else {
                                    break;
                                }
                        } else {
                            marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot0}).addTo(markers_layout).bindPopup('<a style="margin-left:3px" class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a><p style="margin-top: 24px;"><a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id +'/verify">Verify</a></p>');
                            marker.id = response[$i].id;
                        }

                    }
                    map.addLayer(markers_layout);
                },
                error: function (data, textStatus, errorThrown) {
                    console.log(data);
                },
            });
        }

        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] === sParam) { return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]); }
            }
        }
    </script>
    @endif

@endsection
