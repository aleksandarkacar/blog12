@extends('layout.default')


@section('content')

<td><a href="/deletedPosts" class="btn-primary">Show Deleted Posts</a></td>

    <h2>Posts</h2>
    <div class="table-responsive">
        {{ $posts }}
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Body</th>
            <th scope="col">User</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
              <td>{{ $post->id }}</td>
              <td>{{ $post->title }}</td>
              <td>{{ $post->body }}</td>
              <td>{{ $post->user->email }}</td>
              <td><a href="/deletePost/{{ $post->id }}" class="btn-primary">Delete #{{ $post->id }}</a></td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
@endsection