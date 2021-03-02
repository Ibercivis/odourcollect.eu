@extends('layouts.app')

@section('content')

    <script src='https://api.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.css' rel='stylesheet' />

    <!-- MapBox GL JS Draw Library -->
    <script src='https://api.tiles.mapbox.com/mapbox.js/plugins/turf/v3.0.11/turf.min.js'></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.0.9/mapbox-gl-draw.js'></script>
    <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.0.9/mapbox-gl-draw.css' type='text/css'/>

    <!-- MapBox GL JS Geocode Library -->
    <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js'></script>
    <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css' type='text/css' />

    <style>
        button.mapbox-gl-draw_polygon:disabled{
            background-color: grey !important;
            cursor: not-allowed !important;
        }

        .geocoder{margin-bottom: 10px;}

        .mapboxgl-ctrl-geocoder{width: 100%;}
    </style>

    <div class="dashboard-wrapper">
        <div class="row justify-content-center">
            <div class="container-fluid dashboard-content">
                <div class="col-md-12">

                    @if ( count($errors) > 0 )
                        <div class="row">
                            <div class="col">
                                <div class="alert alert-dismissible alert-danger">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Ops!</strong> There are some errors in your information, please check the information and try again.
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="row">
                            <div class="col">
                                <div class="alert alert-dismissible alert-success">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Yes!</strong> {!!session('success')!!}.
                                </div>
                            </div>
                        </div>
                    @endif

                    {!! Form::open(['route' => 'point.save', 'method' => 'POST', 'id' => 'form__create-point', 'class' => '']) !!}
                        <div class="card">
                            <div class="card-header">New activity of interest</div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Center map:</label>
                                        <div id='geocoder' class='geocoder'></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="name">Activity of interest name:</label>
                                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('name') }}</strong></span>
                                                @endif
                                            </div>
                                            <input type="hidden" id="centroid" name="centroid" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="type">Type of activity of interest:</label>
                                                <select id="type" class="form-control" name="type">
                                                    <option value="0">Choose an option</option>
                                                    @foreach ($types as $type)
                                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div id='map' style='width: 100%; height: 500px;'></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-large">SAVE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2xpbmdzaG90MDgiLCJhIjoiY2puMnZzZm02MXN0dzNrcGw1em9yZmZjdCJ9.En40T_Cg1OtKJVpulVLxNg';

        var map = new mapboxgl.Map({
            container: 'map', // container id
            style: 'mapbox://styles/mapbox/streets-v10', //hosted style id
            attributionControl: false,
            center: [2.173404, 41.385063], // starting position
            zoom: 10 // starting zoom
        });

        var centroid = null;

        var geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            placeholder: 'Enter Location',

        });

        document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

        var marker;

        map.on('click', function(e) {

            if (marker !== undefined){marker.remove();}

            marker = new mapboxgl.Marker({draggable: true})
                .setLngLat(e.lngLat)
                .addTo(map);

            marker.on('dragend', onDragEnd);

            $('#centroid').val(JSON.stringify(e.lngLat));
        });

        function onDragEnd() {
            var lngLat = marker.getLngLat();
            coordinates.style.display = 'block';
            coordinates.innerHTML = 'Longitude: ' + lngLat.lng + '<br />Latitude: ' + lngLat.lat;
        }
    </script>

@endsection

