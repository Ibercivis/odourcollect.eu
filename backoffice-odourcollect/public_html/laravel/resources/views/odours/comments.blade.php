@extends('layouts.app')

@section('content')

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
                    @endif
                @endif

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('odour.list')}}" class="breadcrumb-link">Observation list</a></li>
                                            <li class="breadcrumb-item active" aria-current="page"><a href="{{route('odour.show', ['id' => $odour])}}" class="breadcrumb-link">Observation: #{{$odour}}</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Comments</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (sizeof($comments) == 0)
                        <p class="text-center">There are no comments for this odour.</p>
                    @else
                        @foreach ($comments as $comment)
                            <div style="padding: 20px;" class="review-block border-top @if ($comment->hidden ==1){{ 'comment-hidden'}}@endif">
                                <p class="review-text font-italic m-0">“ {{$comment->comment}} ”</p>
                                <span class="text-dark font-weight-bold"><a href="{{ route('user.show', ['id' => $comment->user->id])}}">{{$comment->user->name}} {{$comment->user->surname}}</a></span><small class="text-mute"> ( {{$comment->created_at}} )</small>
                                @if ($comment->hidden != 1)
                                    <p><a class="btn-primary btn-hide" href="{{ route('odour.comment.hide', ['id' => $comment->id])}}">Hide</a></p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
