@extends('layout.default')


@section('content')

 <form class="container mb-5" action="{{ url('editpost/'.$post->id) }}" method="POST">
    @csrf
    <input type="hidden" class="form-control" id="id" name="id" value="{{ $post->id }}" required>
    <div class="mb-3">
      <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
    </div>
    <div class="mb-3">
      <textarea class="form-control" id="body" name="body" required>{{ $post->body }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update post</button>
</form>

<div class="container">
  @include('components.session-handler')
  @include('components.error-handler')
</div>

@endsection