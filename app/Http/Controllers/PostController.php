<?php

namespace App\Http\Controllers;

use App\Mail\PostDeletedMail;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('deleted', 0)->paginate(10);
        foreach ($posts as $post) {
            $post->body = substr($post->body, 0, 100) . '...';
        }
        return view('posts', compact('posts'));
    }

    public function adminIndex()
    {
        $posts = Post::where('deleted', 0)->paginate(10);
        foreach ($posts as $post) {
            $post->body = substr($post->body, 0, 30) . '...';
        }
        return view('adminpanel', compact('posts'));
    }

    public function deletedIndex()
    {
        $posts = Post::where('deleted', 1)->paginate(10);
        foreach ($posts as $post) {
            $post->body = substr($post->body, 0, 30) . '...';
        }
        return view('adminpanel', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:2|max:100|string',
            'body' => 'required|min:10|max:2000|string'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->associate(Auth::user());
        $post->save();

        return redirect('createpost')->with('status', 'Post successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with('comments')->find($id);
        // $post->comments = $post->comments();
        return view('post', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:2|max:100|string',
            'body' => 'required|min:10|max:2000|string'
        ]);
        $post = Post::find($id);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        return redirect('/posts')->with('status', 'Post successfully edited');
    }

    public function editpost($id)
    {
        $post = Post::find($id);
        return view('/editpost', compact('post'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->deleted = true;
        $post->save();

        $mailData = $post->only('title');

        Mail::to($post->user->email)->send(new PostDeletedMail($mailData));

        return redirect('/adminpanel')->with('status', 'Post successfully deleted');
    }
}
