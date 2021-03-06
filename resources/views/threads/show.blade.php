@extends('layouts.app')

@section('header')
<link rel="stylesheet" href="{{ asset('css/vendor/tribute.css') }}">
@endsection

@section('content')
<thread-view inline-template :initial-replies-count="{{ $thread->replies_count }}">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="card mt-4">
					<div class="card-header">
						<div class="level">
							<img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25"
								height="25" class="mr-1">

							<span class="flex">
								<a href="{{ route('profile', $thread->creator) }}">
									{{ $thread->creator->name }}
								</a>
								posted: {{ $thread->title }}
							</span>

							@can ('update', $thread)
							<form action="{{ $thread->path() }}" method="post">
								@csrf
								@method('DELETE')

								<button type="submit" class="btn btn-link">Delete Thread</button>
							</form>
							@endcan
						</div>
					</div>
					<div class="card-body">
						{{ $thread->body }}
					</div>
				</div>

				<replies @added="repliesCount++" @removed="repliesCount--"></replies>

				{{-- @foreach ($replies as $reply)
					@include('threads.reply')
				@endforeach

				{{ $replies->links() }} --}}

				{{-- @if (auth()->check())
					<form action="{{ $thread->path() . '/replies' }}" method="post" class="mt-4">
				@csrf
				<div class="form-group">
					<textarea name="body" id="body" class="form-control" placeholder="Have something to say?" cols="30"
						rows="5"></textarea>
				</div>

				<button type="submit" class="btn btn-primary">Post</button>
				</form>
				@else
				<p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this
					discussion. </p>
				@endif --}}
			</div>
			<div class="col-md-4">
				<div class="card mt-4">
					<div class="card-body">
						<p>
							This thread was published {{ $thread->created_at->diffForHumans() }} by
							<a href="#">{{ $thread->creator->name }}</a>, and currently has <span
								v-text="repliesCount"></span> {{ Str::plural('comment', $thread->replies_count ) }}.
						</p>
						<p>
							{{-- <subscribe-button :active="{{ $thread->isSubscribedTo ? 'true' : 'false' }}">
							</subscribe-button> --}}
							<subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</thread-view>
@endsection