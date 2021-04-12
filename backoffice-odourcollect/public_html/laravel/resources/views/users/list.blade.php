@extends('layouts.app')

@section('content')

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    @if($errors->any())
                        @if($errors->first() == 'error')
                            <div class="card-body border-top">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> Please contact the administrator! 
                                    <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="export">
                @if(Auth::guard('web')->check())
                {!! Form::open(['route' => array('user.download'), 'method' => 'GET', 'id' => 'download', 'name' => 'download', 'class' => 'navbar-form navbar-left']) !!}
                @else
                {!! Form::open(['route' => array('admin.user.download'), 'method' => 'GET', 'id' => 'download', 'name' => 'download', 'class' => 'navbar-form navbar-left']) !!}
                @endif
                    {!! Form::hidden('status_down', '', ['id' => 'status_down']) !!}
                    {!! Form::hidden('permission_down', '', ['id' => 'permission_down']) !!}
                    {!! Form::hidden('map_down', '', ['id' => 'map_down']) !!}
                    {!! Form::hidden('register_date_src_down', '', ['id' => 'register_date_src_down']) !!}
                    {!! Form::hidden('register_date_dst_down', '', ['id' => 'register_date_dst_down']) !!}
                    {!! Form::hidden('publish_date_src_down', '', ['id' => 'publish_date_src_down']) !!}
                    {!! Form::hidden('publish_date_dst_down', '', ['id' => 'publish_date_dst_down']) !!}

                    <div style="width: 90%;">
                        <input style="margin-left: 88%;" id="check" type="submit" class="btn btn-primary" value="Download xlsx">
                    </div>
                {!! Form::close() !!}
            </div>

            <div class="row no_margin selects">
                @if(Auth::guard('web')->check())
                {!! Form::model(Request::all(), ['route' => array('user.list'), 'method' => 'GET', 'id' => 'list_form', 'class' => 'navbar-form navbar-left']) !!}
                @else
                {!! Form::model(Request::all(), ['route' => array('admin.user.list'), 'method' => 'GET', 'id' => 'list_form', 'class' => 'navbar-form navbar-left']) !!}
                @endif
                    <div class="form-group">
                        <label for="season_select"></label>
                        {!! Form::select('status', array('' => 'Status', '1' => 'Active', '0' => 'Desactivated')  , null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'status']) !!}
                        {!! Form::select('permission', array('' => 'Permission', '1' => 'Publish without validation', '0' => 'Publish with validation')  , null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'permission']) !!}
                        {!! Form::select('map', [null => 'Case study'] + $maps->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'onchange' => 'this.form.submit()', 'id' => 'map']) !!}
                    </div>

                    <div class="form-group">
                        <label for="program_type_select">Registered dates (dd/mm/aaaa)</label>
                        <span class="between-dates">From  </span>{!! Form::input('date', 'register_date_src', "", ['class' => 'form-control', 'id' => 'register_date_src_select', 'onchange' => 'this.form.submit()', 'data-date-format' => "yyyy-mm-dd"]) !!}
                        <span class="between-dates">To  </span>{!! Form::input('date', 'register_date_dst', "", ['class' => 'form-control', 'id' => 'register_date_dst_select', 'onchange' => 'this.form.submit()', 'data-date-format' => "yyyy-mm-dd"]) !!}
                    </div>

                    <div class="form-group">
                        <label for="program_type_select">Published observations dates (dd/mm/aaaa)</label>
                        <span class="between-dates">From  </span>{!! Form::input('date', 'publish_date_src', "", ['class' => 'form-control', 'id' => 'publish_date_src_select', 'onchange' => 'this.form.submit()', 'data-date-format' => "yyyy-mm-dd"]) !!}
                        <span class="between-dates">To  </span>{!! Form::input('date', 'publish_date_dst', "", ['class' => 'form-control', 'id' => 'publish_date_dst_select', 'onchange' => 'this.form.submit()', 'data-date-format' => "yyyy-mm-dd"]) !!}
                    </div>
                {!! Form::close() !!}
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">User list</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" data-page-length='10' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>                                            
                                            <th scope="col">Active</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Verified</th>
                                            <th scope="col"></th>
                                            <!-- <th scope="col"></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                @if($user->id_user != '')
                                                <?php $id =  $user->id_user; ?>
                                                @else
                                                <?php $id =  $user->id; ?>
                                                @endif
                                                <th scope="row">{{$id}}</th>

                                                @if ($user->active == 1)
                                                    <td>Yes</td>
                                                @else
                                                    <td>No</td>
                                                @endif
                                                <td>@if($user->type == "AdminZone") Zone Manager @else User @endif</td>
                                                @if ($user->email_verified == 1)
                                                    <td>Yes</td>
                                                @else
                                                    <td>No</td>
                                                @endif
                                                @if(Auth::guard('web')->check())
                                                <td><a class="btn-primary" href="{{ url('user', ['id' => $id])}}">VIEW</a></td>
                                                @else
                                                <td><a class="btn-primary" href="{{ url('admin/user', ['id' => $id])}}">VIEW</a></td>
                                                @endif
                                                <!--
                                                @if(Auth::guard('web')->check())
                                                <td><a class="btn-primary" style="padding: 10px 14px!important;" href="{{ route('user.email', ['id' => $id])}}"><i class="fas fa-envelope"></i></a></td>
                                                @else
                                                <td><a class="btn-primary" style="padding: 10px 14px!important;" href="{{ route('admin.user.email', ['id' => $id])}}"><i class="fas fa-envelope"></i></a></td>
                                                @endif
                                                -->
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

    <script>
        $(document).on('ready', function() {
            $('#table').DataTable({
                ordering: false,
                bInfo : false
            });

            if(getUrlParameter('register_date_src')){
                $('#register_date_src_select').val(getUrlParameter('register_date_src'));
            }

            if(getUrlParameter('register_date_dst')){
                $('#register_date_dst_select').val(getUrlParameter('register_date_dst'));
            }

            if(getUrlParameter('publish_date_src')){
                $('#publish_date_src_select').val(getUrlParameter('publish_date_src'));
            }

            if(getUrlParameter('publish_date_dst')){
                $('#publish_date_dst_select').val(getUrlParameter('publish_date_dst'));
            }

            $('#check').on("click", function(event) {
                event.preventDefault();

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

        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] === sParam) {return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);}
            }
        }
    </script>

@endsection
