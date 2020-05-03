
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="content">
            @include('includes.message')
            {{--                <div class="card-body">--}}
            {{--                    @if (session('status'))--}}
            {{--                        <div class="alert alert-success" role="alert">--}}
            {{--                            {{ session('status') }}--}}
            {{--                        </div>--}}
            {{--                    @endif--}}

            {{--                    You are logged in!--}}
            {{--                </div>--}}
            <div class="row">
                <div class="col-3">

                </div>




                <div class="col-6">
                    <form class="searchbar" method="get" action="/users">
                        <input class="search_input" type="text" name="q" placeholder="Search...">
                        <button type="submit" class="search_icon"><i class="fas fa-search"></i></button>
                    </form>
                    <div class="list-group">
                        @foreach($users as $user)
                            <a href="{{ route('user.index',$user->id) }}" class="list-group-item d-flex list-group-item-action flex-row align-items-start">
                                <div class="col-4">
                                    <img  src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="float-left img-thumbnail">
                                </div>
                                <div class="float-right col-8 d-flex flex-column">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $user->name }}</h5>
                                    </div>
                                    <small class="font-italic">{{ $user->email }}</small>
                                    @if(is_null(Auth::user()->friends()->find($user->id)))
                                        <button type="button" class="ml-5 mr-5 mt-5 btn btn-outline-primary" userid="{{ $user->id }}" id="changeFriend">Дружить</button>
                                    @else
                                        <button type="button" class="ml-5 mr-5 mt-5 btn btn-primary" userid="{{ $user->id }}" id="changeFriend">Вы дружите</button>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>



                <div class="col-3">

                </div>

            </div>

        </div>
    </div>
    </div>
@endsection
