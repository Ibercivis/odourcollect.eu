@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>

    <style>
        .leaflet-control-attribution{display: none!important;}
    </style>

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <div class="row">
                @if($errors->any())
                   @if($errors->first() == 'success')
                        <div class="card-body border-top">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Done!</strong> It has been updated correctly
                                <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
                
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('zone.list')}}" class="breadcrumb-link">Case study list</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">{{$zone->name}}</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="button-track" style="float: left;margin-top: -15px;margin-bottom: 10px;">
                                    <?php if(Auth::guard('web')->check()){ ?>
                                         <form method="GET" action="{{ route('web.odour.track', ['id' => $zone->id]) }}" accept-charset="UTF-8" id="download" name="download" class="navbar-form navbar-left">
                                    <?php } else { ?>
                                        <form method="GET" action="{{ route('admin.odour.track', ['id' => $zone->id]) }}" accept-charset="UTF-8" id="download" name="download" class="navbar-form navbar-left">
                                    <?php } ?>
                                    
                                    <input id="status_down" name="status_down" type="hidden" value="">
                                    <input id="permission_down" name="permission_down" type="hidden" value="">
                                    <input id="map_down" name="map_down" type="hidden" value="">
                                    <input id="register_date_src_down" name="register_date_src_down" type="hidden" value="">
                                    <input id="register_date_dst_down" name="register_date_dst_down" type="hidden" value="">
                                    <input id="publish_date_src_down" name="publish_date_src_down" type="hidden" value="">
                                    <input id="publish_date_dst_down" name="publish_date_dst_down" type="hidden" value="">

                                    <div style="width: 90%;">
                                        <input style="margin-left: 0%;" id="check" type="submit" class="btn btn-primary" value="Download Track">
                                    </div>
                                </form>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        {!! Form::open(['route' => 'zone.store', 'method' => 'POST', 'id' => 'form__create-zone', 'class' => '']) !!}
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <nav id="menu"></nav>
                                            <div id='map' style='width: 100%; height: 500px;'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="map_id" value="{{$zone->id}}">
                        {!! Form::close() !!}
                    </div>

                    <div class="card">
                        <h5 class="card-header">Add users to the case study: <strong>{{$zone->name}}</strong></h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" data-page-length='10' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{$user->id}} ({{$user->type}})</td>
                                                <?php if(Auth::guard('web')->check()){ ?>
                                                @if ($user->belong == true)
                                                    <td><a id="myBtn-{{$user->id}}" data-url="{{ route('zone.remove.user', ['id' => $zone->id, 'user' => $user->id])}}" class="modal__trigger btn-danger">DELETE</a></td>
                                                @else
                                                    <td><a class="btn-primary" href="{{ route('zone.add.user', ['id' => $zone->id, 'user' => $user->id])}}">ADD</a></td>
                                                @endif
                                                <?php } else { ?>
                                                @if ($user->belong == true)
                                                    <td><a id="myBtn-{{$user->id}}" data-url="{{ route('admin.zone.remove.user', ['id' => $zone->id, 'user' => $user->id])}}" class="modal__trigger btn-danger">DELETE</a></td>
                                                @else
                                                    <td><a class="btn-primary" href="{{ route('admin.zone.add.user', ['id' => $zone->id, 'user' => $user->id])}}">ADD</a></td>
                                                @endif
                                                <?php } ?>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close close_action">&times;</span>
                            <p class="modal_title">¿Are you sure to delete it?</p>
                            <div style="display: flex">
                                <a id="accept" href="" class="btn-danger modal_button">YES</a>
                                <a class="btn-cancel close_action modal_button">CANCEL</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script>
        map_id ='';

        $(document).on('ready', function() {
            $('#table').DataTable({
                ordering: false,
                bInfo : false
            });

            map_id = $('#input[name=map_id]').val();

            var modal = document.getElementById('myModal');
            $(document).on('click','[id^="myBtn"]', function() {
                modal.style.display = "block";
                url = $(this).data('url');

                $('#accept').attr("href",url);
            }); 

            $('.close_action').on('click', function() {
                modal.style.display = "none";
            });

            window.onclick = function(event) {
                if (event.target == modal) {modal.style.display = "none";}
            }
        });

        var map = L.map( 'map', {
            center: [41.385063, 2.173404],
            minZoom: 2,
            maxZoom: 18,
            zoom: 12,
            zoomControl:false
        });

        L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2xpbmdzaG90MDgiLCJhIjoiY2puMnZzZm02MXN0dzNrcGw1em9yZmZjdCJ9.En40T_Cg1OtKJVpulVLxNg').addTo(map)

        $.ajaxSetup({
            headers: { 'X-XSRF-Token': $('meta[name="_token"]').attr('content') }
        });

        $.ajax({
            url: '{{ route('zone.points', ['id' => $zone->id]) }}',
            type: "GET",
            beforeSend : function() {},
            success: function(response) {
                var geojson = [];

                for (e = 0; e < response[0].points.length; e++){
                    geojson.push([ parseFloat(response[0].points[e].latitude), parseFloat(response[0].points[e].longitude) ]);
                }

                var polygon = L.polygon(geojson).addTo(map);
                map.fitBounds(geojson);

            }
        });
    </script>

@endsection
