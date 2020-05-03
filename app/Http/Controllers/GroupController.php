<?php

namespace App\Http\Controllers;

use App\Group;
use App\Like;
use App\Post;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    public function index(Request $request)
    {
        if($request->has('q')){
            $groups = Group::where('title', 'LIKE', '%'.$request->input('q').'%')->orWhere('description', 'LIKE', '%'.$request->input('q').'%')->get();
        }else{
            $groups = Group::all();
        }
        return view('groups.home', ['groups' => $groups]);
    }

    public function getGroup($id)
    {
        $posts = Group::find($id)->posts()->orderBy('created_at', 'desc')->get();
        $group = Group::find($id);
        $likes = Like::all();
        return view('groups.index', ['posts' => $posts, 'group' => $group, 'likes' => $likes]);
    }

    public function createPost(Request $request, $id)
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
        $group = Group::find($id);
        $message = "Ошибка при добавлении поста";
        if($group->posts()->save($post)){
            $message = "Пост успешно создан";
        }
        return redirect(route('groups.index',$id))->with(['message' => $message]);
    }

    public function deletePost()
    {

    }
}
