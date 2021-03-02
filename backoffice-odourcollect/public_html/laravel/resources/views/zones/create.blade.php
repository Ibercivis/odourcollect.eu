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
                    
                    {!! Form::open(['route' => 'zone.store', 'method' => 'POST', 'id' => 'form__create-zone', 'class' => '']) !!}
                    
                        <div class="card">
                            <div class="card-header">New case study</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Center map:</label>
                                        <div id='geocoder' class='geocoder'></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="name">Case study name:</label>
                                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                                                  @if ($errors->has('name'))
                                                      <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('name') }}</strong></span>
                                                  @endif
                                            </div>
                                            <input type="hidden" id="centroid" name="centroid" value="">
                                            <input type="hidden" id="polygon" name="polygon" value="">
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

        var polygon = null;
        var centroid = null;
        var centerMarker = null;

        var mapDrawStyles = [
            // ACTIVE (being drawn)
            // line stroke
            {
              "id": "gl-draw-line",
              "type": "line",
              "filter": ["all", ["==", "$type", "LineString"], ["!=", "mode", "static"]],
              "layout": {
                "line-cap": "round",
                "line-join": "round"
              },
              "paint": {
                "line-color": "#D20C0C",
                "line-dasharray": [0.2, 2],
                "line-width": 2
              }
            },

            // polygon fill
            {
              "id": "gl-draw-polygon-fill",
              "type": "fill",
              "filter": ["all", ["==", "$type", "Polygon"], ["!=", "mode", "static"]],
              "paint": {
                "fill-color": "#D20C0C",
                "fill-outline-color": "#D20C0C",
                "fill-opacity": 0.1
              }
            },

            // polygon outline stroke
            // This doesn't style the first edge of the polygon, which uses the line stroke styling instead
            {
              "id": "gl-draw-polygon-stroke-active",
              "type": "line",
              "filter": ["all", ["==", "$type", "Polygon"], ["!=", "mode", "static"]],
              "layout": {
                "line-cap": "round",
                "line-join": "round"
              },
              "paint": {
                "line-color": "#D20C0C",
                "line-dasharray": [0.2, 2],
                "line-width": 2
              }
            },

            // vertex point halos
            {
              "id": "gl-draw-polygon-and-line-vertex-halo-active",
              "type": "circle",
              "filter": ["all", ["==", "meta", "vertex"], ["==", "$type", "Point"], ["!=", "mode", "static"]],
              "paint": {
                "circle-radius": 5,
                "circle-color": "#FFF"
              }
            },

            // vertex points
            {
              "id": "gl-draw-polygon-and-line-vertex-active",
              "type": "circle",
              "filter": ["all", ["==", "meta", "vertex"], ["==", "$type", "Point"], ["!=", "mode", "static"]],
              "paint": {
                "circle-radius": 3,
                "circle-color": "#D20C0C",
              }
            },


            // INACTIVE (static, already drawn)
            // line stroke
            {
              "id": "gl-draw-line-static",
              "type": "line",
              "filter": ["all", ["==", "$type", "LineString"], ["==", "mode", "static"]],
              "layout": {
                "line-cap": "round",
                "line-join": "round"
              },
              "paint": {
                "line-color": "#000",
                "line-width": 3
              }
            },

            // polygon fill
            {
              "id": "gl-draw-polygon-fill-static",
              "type": "fill",
              "filter": ["all", ["==", "$type", "Polygon"], ["==", "mode", "static"]],
              "paint": {
                "fill-color": "#000",
                "fill-outline-color": "#000",
                "fill-opacity": 0.1
              }
            },

            // polygon outline
            {
              "id": "gl-draw-polygon-stroke-static",
              "type": "line",
              "filter": ["all", ["==", "$type", "Polygon"], ["==", "mode", "static"]],
              "layout": {
                "line-cap": "round",
                "line-join": "round"
              },
              "paint": {
                "line-color": "#000",
                "line-width": 3
              }
            }
          ];

        var draw = new MapboxDraw({
            displayControlsDefault: false,
            controls: {
                polygon: true,
                trash: true
            },
            styles: mapDrawStyles
        });

        map.addControl(draw);
        draw.changeMode('draw_polygon');

        var geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            placeholder: 'Enter Location',

        });

        document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

        map.on('draw.create', createPolygon);
        map.on('draw.delete', deletePolygon);
        map.on('draw.update', updatePolygon);

        geocoder.on('result', function(ev) {
            draw.trash();
        });

        function createPolygon(e) {
            polygon = draw.getAll();

            $('.mapbox-gl-draw_polygon').prop('disabled', true);

            if (polygon.features.length > 0) {
                centroid = turf.centroid(polygon);
                centerMarker = new mapboxgl.Marker()
                  .setLngLat(centroid.geometry.coordinates)
                  .addTo(map);
            }

            setInputs(centroid, polygon);
        }

        function deletePolygon(e) {
            draw.trash();

            $('.mapbox-gl-draw_polygon').prop('disabled', false);

            draw.changeMode('draw_polygon');

            if(centerMarker != null){
                centerMarker.remove();
                centerMarker = null;
            }

            polygon = null;
            centroid = null;

            setInputs('', '');
        }

        function updatePolygon(e) {
          polygon = draw.getAll();

          $('.mapbox-gl-draw_polygon').prop('disabled', true);

          if (polygon.features.length > 0) {
              centroid = turf.centroid(polygon);

              if(centerMarker != null){ centerMarker.remove(); };
              centerMarker = new mapboxgl.Marker()
                .setLngLat(centroid.geometry.coordinates)
                .addTo(map);

          }
          setInputs(centroid, polygon);
        }

        function setInputs(c, p){
          $('#centroid').val(JSON.stringify(c));
          $('#polygon').val(JSON.stringify(p));
        }
    </script>

@endsection

