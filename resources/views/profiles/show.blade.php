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
        
            @forelse ($activities as $date => $activity)
                <div class="pb-2 mt-4 mb-2 border-bottom">
                   <h5>
                       {{ $date }}
                    </h5> 
                </div>
                @foreach ($activity as $record)
                    @if (view()->exists("profiles.activities.{$record->type}"))
                            {{-- {{ $record }} --}}
                        @include("profiles.activities.{$record->type}", ['activity' => $record])
                    @endif
                @endforeach
            @empty
                <p>There is no activity for this user yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection