<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function createPost(Request $request)
    {
        $this->validate($request, [
            'body' => 'required|max:1000',
            'image' => 'image:jpeg:png:gif:webp'
        ]);
        if(($request->has('image'))){
            $path = $request->file('image')->store('public/images');
            $post = new Post();
            $post->body = $request->input('body');
            $post->filetype = $request->file('image')->getClientOriginalExtension();
            $post->image =  $path;
        }else {
            $post = new Post();
            $post->body = $request->input('body');
            $post->image = 0;
            $post->filetype = 0;
        }
        $message = "Ошибка при добавлении поста";
        if($request->user()->posts()->save($post)){
            $message = "Пост успешно создан";
        }
        return redirect()->back()->with(['message' => $message]);
    }


    public function editPost(Request $request, $id){
        $post_id = $request->input('postId');
        $post = Post::where('id',$id)->first();
        if(!is_null($post->user_id)) {
            if ($post->user_id != Auth::id()) {
                return response()->json(['error' => 'Ошибка редактирования']);
            }
        }else{
            if ($post->group_id != $request->input('group_id')) {
                return response()->json(['error' => 'Ошибка редактирования']);
            }
        }
        $message = "";
        $post->body = $request->input('body');
        if($post->save() ){
            $message = 'Пост успешно обновлен';
        }
        return response()->json(['message' => $message]);

    }
    public function addLike(Request $request)
    {
        $post_id = $request->input('postId');
        $user = $request->user();
        $is_like = $request->input('isLike') === 'true';
        $update = false;
        $post = Post::find($post_id);

        if(!$post_id){
            return null;
        }
        $like = $user->likes()->where('post_id',$post_id)->first();
        if($like){
            $alreadyLiked = $like->like;
            $update = true;
            if($alreadyLiked == $is_like){
                $like->delete();
                return null;
            }
        }else{
            $like = new Like();
        }
        $like->like = $is_like;
        $like->user_id = $user->id;
        $like->post_id = $post_id;
        if ($update) $like->update();
            else $like->save();
        return null;
    }

    public function deletePost(Request $request, $id)
    {
       $post = Post::where('id',$id)->first();

        if(!is_null($post->user_id)) {
            if ($post->user_id != Auth::id()) {
                return redirect()->back()->withErrors("Ошибка удаления");
            }
        }else{
            if ($post->group_id != $request->input('group_id')) {
                return redirect()->back()->withErrors("Ошибка удаления");
            }
        }
        $message = "";
        if($post->delete() ){
               $message = 'Пост успешно удален';
        }
       return redirect()->back()->with(['message' => $message]);
    }
}
