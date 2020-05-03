
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
                        <form class="searchbar" method="get" action="/groups">
                            <input class="search_input" type="text" name="q" placeholder="Search...">
                            <button type="submit" class="search_icon"><i class="fas fa-search"></i></button>
                        </form>
                    <div class="list-group">
                        @foreach($groups as $group)
                        <a href="{{ route('groups.index',$group->id) }}" class="list-group-item d-flex list-group-item-action flex-row align-items-start">
                            <div class="col-4">
                            <img  src="{{ Storage::url($group->logotype) }}" alt="{{ $group->title }}" class="float-left img-thumbnail">
                            </div>
                                <div class="float-right col-8">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $group->title }}</h5>
                                <small>{{ $group->members }}</small>
                            </div>
                            <p class="mb-1">{{ $group->description }}</p>
                            <small class="font-italic">{{ $group->theme }}</small>
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
