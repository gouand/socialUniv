<?php

namespace App\Http\Controllers;

use App\FriendUser;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if($request->has('q')){
            $users = User::where('name', 'LIKE', '%'.$request->input('q').'%')->orWhere('email', 'LIKE', '%'.$request->input('q').'%')->get();
        }else{
            $users = User::all();
        }
        return view('users.home',['users' => $users]);
    }

    public function getFriends(Request $request)
    {
        $users = Auth::user()->friends()->get();
        return view('users.friends', ['users'=>$users]);
    }

    public function addFriend(Request $request, $id)
    {
        $user = User::find(Auth::id());
        $message = '';
        if(!is_null($user->friends()->find($id))){
            return response()->json(['message' => "Добавлен"]);
        }else{

            if(isset($id) && $user->friends()->attach([$id])){
                $message = 'Добавлен';
            }
        }
        return response()->json(['message' => $message]);
    }

    public function removeFriend(Request $request, $id)
    {
        $user = User::find(Auth::id());
        $message = '';
        if(is_null($user->friends()->find($id))){
            return response()->json(['message' => "Удален"]);
        }else{

            if($user->friends()->detach([$id])){
                $message = 'Удален';
            }
        }
        return response()->json(['message' => $message]);
    }

    public function AuthRouteAPI(Request $request){
        return $request->user();
    }

    public function addGroup(Request $request){
        $validatedData = Validator::make($request->all(), [
            'description' => 'required|max:1000',
            'logotype' => 'file:jpeg:png:gif:webp',
            'title' => 'required|max:255',
            'theme' => 'required|max:255'
        ]);
        $group = new Group();
        $group->user_id = Auth::id();
        $group->description = $request->input('description');
        $group->logotype = $request->has('logotype') ? $request->file('logotype')->store('public/groups/avatars') : "public/avatars/default.png";
        $group->title = $request->input('title');
        $group->theme = $request->input('theme');

        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
        if(!$group->save()){
            $message = "Ошибка создания поста";
        }else{
            $message = "Группа кспешно создана";
        }
        return redirect()->back()->with(['message' => $message]);
    }
}
