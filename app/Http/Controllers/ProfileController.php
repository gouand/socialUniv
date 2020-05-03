<?php

namespace App\Http\Controllers;

use App\Group;
use App\Like;
use App\User;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        $posts = Auth::user()->posts()->orderBy('created_at', 'desc')->get();
        $user = Auth::user();
        $likes = Like::all();
        return view('profile.home', ['posts' => $posts, 'user' => $user, 'likes' => $likes]);
    }

    public function getUserProfile(Request $request, $id)
    {

        if(!$id)
            abort(404);
        $posts = User::find($id)->posts()->orderBy('created_at', 'desc')->get();
        $posts ? $posts : $posts = NULL;
        $user = User::find($id);
        $likes = Like::all();
        return view('profile.home', ['posts' => $posts, 'user' => $user, 'likes' => $likes]);
    }
    public function createPost(Request $request)
    {
        $this->validate($request, [
            'body' => 'required|max:1000',
            'image' => 'image:jpeg:png:gif:webp'
        ]);
        $post = new Post();
        if(($request->has('image'))){
            $path = $request->file('image')->store('public/images');

            $post->body = $request->input('body');
            $post->filetype = $request->file('image')->getClientOriginalExtension();
            $post->image =  $path;
        }else {
            $post->body = $request->input('body');
            $post->image = 0;
            $post->filetype = 0;
        }
        $user = Auth::user();
        $message = "Ошибка при добавлении поста";
        if($user->posts()->save($post)){
            $message = "Пост успешно создан";
        }
        return redirect()->back()->with(['message' => $message]);
    }
}
