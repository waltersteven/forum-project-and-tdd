@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="pb-2 mt-4 mb-2 border-bottom">
                <h2>
                    {{ $profileUser->name }}
                    <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
                </h2>
            </div>
        
            @foreach ($activities as $date => $activities)
                <div class="pb-2 mt-4 mb-2 border-bottom">
                   <h5>
                       {{ $date }}
                    </h5> 
                </div>
                @foreach ($activities as $activity)
                    @include("profiles.activities.{$activity->type}")
                @endforeach
            @endforeach
        </div>
    </div>
</div>
@endsection