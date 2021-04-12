@extends('layouts.app')

@section('content')

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                    <div id="error" class="hide">
                        <div class="col">
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Ops!</strong> Check all the fields.
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="col">
                            <div class="alert alert-dismissible alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Yes!</strong> {!!session('success')!!}
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        @if($errors->first() == 'error')
                            <div class="card-body border-top">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> This email already exists!
                                    <a href="#" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                                </div>
                            </div>
                        @endif

                        @if($errors->first() == 'success')
                            <div class="card-body border-top">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Done!</strong>It has been updated correctly!
                                    <a href="#" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                               <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('user.list')}}" class="breadcrumb-link">User list</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">{{$user->id}}</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-header">Edit user information</div>
                                <div class="card-body">
                                   
                                    @if(Auth::guard('web')->check())
                                     {!! Form::open(['route' => 'user.update', 'method' => 'POST', 'id' => 'updateUserForm', 'class' => '']) !!}
                                    @else
                                     {!! Form::open(['route' => 'admin.user.update', 'method' => 'POST', 'id' => 'updateUserForm', 'class' => '']) !!}
                                    @endif
                                        <div class="form-group">
                                            <label for="user_id">Id</label>
                                            <input type="text" id="user_id" name="user_id" value="{{$user->id}}" readonly style="border:0"> 
                                        </div>
                                        @if(Auth::guard('web')->check())
                                        @else
                                        <div class="form-group">
                                            
                                            <label for="new_email">Type user</label>
                                            <select name="type" id="" class="form-control">
                                                <option value="User" <?php if($user->type == 'User') { echo 'selected'; } ?>>User</option>
                                                @if(!$zones_user->isEmpty())
                                                <option value="AdminZone" <?php if($user->type == 'AdminZone') { echo 'selected'; } ?>>Zone Manager</option>
                                               @endif
                                            </select>
                                            <small>Remember that in order to add a user as zone admin, you must have a zone assigned</small>
                                            <?php if($user->type == 'AdminZone') { ?>
                                            <div class="form-group">
                                               
                                            <label for="new_email" style="margin-top: 20px;
                                            margin-bottom: 20px;width: 100%;">Select Admin Zone</label>
                                              @if(!empty($zones_user))

                                              @foreach($zones_user as $zones)
                                              
                                              <div>
                                              <label for="zone"><?php echo $zones->name; ?></label>
                                              <input type="checkbox" name="zone[]" <?php if($zones->admin == "1"){ echo "checked";} ?> value="<?php echo $zones->id; ?>">
                                            </div>
                                              @endforeach
                                              @endif
                                          </div>
                                            <?php } ?>
                                        </div>
                                        @endif
                                        
                                        <input type="hidden" id="id" name="id" value="{{$user->id}}">

                                        <div id="error" class="alert alert-danger hide" role="alert">
                                            <strong>ERROR</strong> Check all the fields
                                        </div>

                                        <a onclick="checkForm()" class="btn btn-primary btn-large">Save</a>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <!--
                            <div class="card">
                                <div class="card-header">Reset user password</div>
                                <div class="card-body">
                                    {!! Form::open(['route' => 'user.update.password', 'method' => 'POST', 'id' => 'updatePassword', 'class' => '']) !!}
                                        <label for="new_password">Password</label>
                                        <div class="input-group mb-3">
                                            <input id="new_password" name="password" type="password" placeholder="Password" class="form-control" value="">
                                            <div class="input-group-append">
                                                <a onclick="generatePassword()" id="generate_password" type="button" class="btn btn-aux">Generate</a>
                                            </div>
                                        </div>

                                        <input type="hidden" id="id_user" name="id_user" value="{{$user->id}}">

                                        <div id="error" class="alert alert-danger hide" role="alert">
                                            <strong>ERROR</strong> Check all the fields
                                        </div>

                                        <a onclick="checkFormPassword()" class="btn btn-primary btn-large">Save</a>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            -->
                            <div class="card">
                                <div class="card-header">Grant permission to publish without validation</div>
                                <div class="card-body">
                                    <div class="switch-button switch-button-success">
                                        {!! Form::open(['route' => 'user.update.permission', 'method' => 'POST', 'id' => 'updateUserPermission', 'class' => '']) !!}
                                            <input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">
                                            <input type="checkbox" @if ($user->without_validation == 1){{'checked'}}@endif name="permission" id="switch"><span>
                                            <label for="switch"></label></span>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Odours views</h5>
                                <h2 class="mb-0">{{$user->visits}}</h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-eye fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Comments</h5>
                                <h2 class="mb-0">{{$user->comments}}</h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-comment-alt fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Confirmations</h5>
                                <h2 class="mb-0">{{$user->confirmations}}</h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-check-circle fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Logins</h5>
                                <h2 class="mb-0">{{$user->count}}</h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-sign-in-alt fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Last login</h5>
                                <h2 class="mb-0">{{$user->last_login}}</h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-calendar-alt fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Odours list</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" data-page-length='5' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Odour type</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Verified</th>
                                            <th scope="col">Published at</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($user->odours as $odour)
                                        <tr>
                                            <th scope="row">{{$odour->id}}</th>
                                            <td>{{$odour->odour_type}}</td>
                                            <td>{{$odour->address}}</td>
                                            @if ($odour->verified == 1)
                                                <td>Yes</td>
                                            @else
                                                <td><a class="btn-primary" href="{{ route('odour.verify', ['id' => $odour->id])}}">Verify</a></td>
                                            @endif
                                            <td>{{$odour->published_at}}</td>
                                            @if(Auth::guard('web')->check())
                                                <td><a class="btn-primary" href="{{ route('odour.show', ['id' => $odour->id])}}">VIEW</a></td>
                                                @else
                                                <td><a class="btn-primary" href="{{ url('admin/odour', ['id' => $odour->id])}}">VIEW</a></td>
                          
                                                @endif
                                            
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Zone list</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table2" data-page-length='5' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Place</th>
                                            <th scope="col">Region</th>
                                            <th scope="col">Country</th>
                                            <th scope="col"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->zones as $zone)
                                            <tr>
                                                <th scope="row">{{$zone->id}}</th>
                                                <td>{{$zone->name}}</td>
                                                <td>{{$zone->place}}</td>
                                                <td>{{$zone->region}}</td>
                                                <td>{{$zone->country}}</td>
                       
                                                @if ($zone->belong == true)
                                                    @if(Auth::guard('web')->check())
                                                    <td><a id="myBtn-{{$zone->id}}" data-url="{{ route('zone.remove.user', ['id' => $zone->id, 'user' => $user->id])}}" class="modal__trigger btn-danger">DELETE</a></td>
                                                    @else
                                                    <td><a id="myBtn-{{$zone->id}}" data-url="{{ route('admin.zone.remove.user', ['id' => $zone->id, 'user' => $user->id])}}" class="modal__trigger btn-danger">DELETE</a></td>
                                                    @endif
                                                    
                                                @else
                                                    @if(Auth::guard('web')->check())
                                                    <td><a class="btn-primary" href="{{ route('zone.add.user', ['id' => $zone->id, 'user' => $user->id])}}">ADD</a></td>
                                                    @else
                                                    <td><a class="btn-primary" href="{{ route('admin.zone.add.user', ['id' => $zone->id, 'user' => $user->id])}}">ADD</a></td>
                                                    @endif
                                                    
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Email list <span style="float: right;"><a class="btn-primary" style="padding: 7px 11px!important;" href="{{ route('user.email', ['id' => $user->id])}}"><i class="fas fa-envelope"></i></a></span></h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table3" data-page-length='5' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Subject</th>
                                            <th scope="col">Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->emails as $email)
                                            <tr>
                                                <td>{{$email->created_at->format('j F')}}</td>
                                                <td>{{$email->subject}}</td>
                                                <td>{{$email->body}}</td>
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

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).on('ready', function() {
            $('#table').DataTable({
                ordering: false,
                bInfo : false
            });

            $('#table2').DataTable({
                ordering: false,
                bInfo : false
            });

            $('#table3').DataTable({
                ordering: false,
                bInfo : false
            });

            $('#switch').change(function(){
                $( "#updateUserPermission" ).submit();
            });

            var modal = document.getElementById('myModal');
            $('[id^="myBtn"]').on('click', function() {
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

        function checkForm(){
            event.preventDefault();

            var username = $('#new_username');
           
            var email = $('#new_email');

            username.removeClass('error');
            
            email.removeClass('error');

            var error = document.getElementById("error");

            var valid = true;

            if (!validateTextInput(username)) { valid = false; username.addClass('error'); }
           
            if (!validateEmailInput(email)) { valid = false; email.addClass('error');}

            if (valid){
                error.classList.add("hide");
                $( "#updateUserForm" ).submit();
            } else {
                error.classList.remove("hide");
            }
        }

        function checkFormPassword(){
            event.preventDefault();

            var password = $('#new_password');
            password.removeClass('error');

            var error = document.getElementById("error");
            var valid = true;

            if (!validateTextInput(password)) { valid = false; password.addClass('error'); }

            if (valid){
                error.classList.add("hide");
                $( "#updatePassword" ).submit();
            } else {
                error.classList.remove("hide");
            }
        }

        function generatePassword(){
            $('#new_password').val(randString(12));
        }

        function validateTextInput(input) {
            var value = input.val();
            if (value) {
                input.removeClass("invalid").addClass("valid");
                return true;
            } else {
                input.removeClass("valid").addClass("invalid");
                return false;
            }
        }

        function validateEmailInput(input) {
            var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            var value = input.val();

            var is_email = re.test(input.val());

            if (value) {
                if (is_email) {
                    input.removeClass("invalid").addClass("valid");
                    return true;
                } else {
                    input.removeClass("valid").addClass("invalid");
                    return false;
                }
            } else {
                input.removeClass("valid").addClass("invalid");
                return false;
            }
        }

        function randString(length){
            var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
            var pass = "";
            for (var x = 0; x < length; x++) {
                var i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            return pass;
        }
    </script>

@endsection

