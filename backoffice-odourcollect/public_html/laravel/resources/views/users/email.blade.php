@extends('layouts.app')

@section('content')

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header">
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('user.list')}}" class="breadcrumb-link">User list</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.show', ['id' => $user->id])}}" class="breadcrumb-link">{{$user->name}} {{$user->surname}}</a></li>
                                    <li class="breadcrumb-item">New email</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                    <div id="error" class="hide">
                        <div class="col">
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" onclick="closeError()">&times;</button>
                                <strong>Ops!</strong> Check all the fields.
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        @if($errors->first() == 'success')
                            <div class="card-body border-top">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Done!</strong> The email has been sent correctly
                                    <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="email-head">
                <div class="email-head-title">Compose new email<span class="icon mdi mdi-edit"></span></div>
            </div>

            {!! Form::open(['route' => 'user.send.email', 'method' => 'POST', 'id' => 'sendEmailForm', 'class' => '']) !!}
                <div class="email-compose-fields">
                    <div class="to">
                        <div class="form-group row pt-0">
                            <label class="col-md-1 control-label">To:</label>
                            <div class="col-md-11">
                                <select class="js-example-basic-multiple" name="email[]" id="email" multiple>
                                    <option value="{{$user->email}}" selected>{{$user->email}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="subject">
                        <div class="form-group row pt-2">
                            <label class="col-md-1 control-label">Subject</label>
                            <div class="col-md-11">
                                <input class="form-control" type="text" name="subject" id="subject">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="email editor">
                    <div class="col-md-12 p-0">
                        <div class="form-group">
                            <label class="control-label sr-only" for="summernote">Descriptions </label>
                            <textarea class="form-control" id="body" name="body" rows="6" placeholder="Write Descriptions"></textarea>
                        </div>
                        <a onclick="checkForm()" class="btn btn-primary btn-large">Send</a>

                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({ tags: true });
        });

        function closeError(){
            var error = document.getElementById("error");
            error.classList.add("hide");

            console.log('hide');
        }

        function checkForm() {
            event.preventDefault();

            emails = [];
            $.each($("#email option:selected"), function(){
                emails.push($(this).val());
            });

            var subject = $('#subject');
            var body = $('#body');

            $('#email').removeClass('error');
            subject.removeClass('error');
            body.removeClass('error');

            var error = document.getElementById("error");

            var valid = true;

            if (emails.length === 0){
                valid = false;
                $('#email').addClass('error')
            } else {
                for (i = 0; i < emails.length; i++){
                    if (!validateEmailInput(emails[i])) { valid = false; $('#email').addClass('error'); break;}
                }
            }

            if (!validateTextInput(subject)) { valid = false; subject.addClass('error'); }
            if (!validateTextInput(body)) { valid = false; body.addClass('error');}

            if (valid){
                error.classList.add("hide");
                $( "#sendEmailForm" ).submit();
            } else {
                error.classList.remove("hide");
            }
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
            var value = input;
            var is_email = re.test(input);

            if (value) {
                if (is_email) {return true;
                } else {return false;}
            } else {return false;}
        }

    </script>

@endsection
