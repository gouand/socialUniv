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
                <section class="row new-post">
                    <div class="col-md-6 offset-md-3">
                        <header><h3>Хотите что-то сказать?</h3></header>
                        <form action="{{ route('createPost') }}"  enctype="multipart/form-data" method="post">
                            <div class="form-group">
                                <textarea class="form-control" name="body" id="new-post" rows="5" placeholder="Ваш пост"></textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <label class="upload-image" for="post-image"></label>
                            <input id="post-image" style="display:none;" type="file" class="btn upload-image" name="image">
                                <button type="submit" class="btn btn-primary">Запостить</button>
                                <input  type="hidden" value="{{ Session::token() }}" name="_token">


                            </div>
                        </form>
                    </div>
                </section>
                <section class="row posts">
                    <div class="col-md-6 offset-md-3">
                        <header><h3>Что говорят другие...</h3></header>
                        @if(count($posts) > 0)
                        @foreach($posts as $post)
                            <article class="mt-3">
                                @if(Storage::exists($post->image))
                            <img src="{{ Storage::url($post->image)  }}" class="w-100 img-fluid" alt="Responsive image">
                            @endif
                                    <div class="post" data-postid="{{ $post->id }}">
                                <p>{{$post->body}}</p>
                                <div class="info">
                                    Posted by {{ isset($post->user->name) ? $post->user->name : $post->group->title }} on {{ $post->created_at }}
                                </div>
                                <div class="interaction">
                                    <i class="like cursor_pointer {{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 1 ? 'fas fa-thumbs-up' : 'far fa-thumbs-up' : 'far fa-thumbs-up'}}"><small class="font-10 ml-1 d-inline mb-1 text-muted">{{ $likes->where('post_id', $post->id)->where('like', 1)->count() }}</small></i>
                                    <i class="like cursor_pointer {{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 0 ? 'fas fa-thumbs-down' : 'far fa-thumbs-down' : 'far fa-thumbs-down'}}"><small class="font-10 d-inline ml-1 text-muted">{{ $likes->where('post_id', $post->id)->where('like', 0)->count() }}</small></i>
                                    <a href="#" class="edit">Edit</a>
                                    @if(Auth::user() == $post->user)
                                    <a class="like" href="{{ route('home') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('delete-post-{{$post->id}}-form').submit();">
                                        {{ __('remove') }}
                                    </a>
                                    @endif;
                                    <form id="delete-post-{{$post->id}}-form" action="{{ route('deletePost', ['id' => $post->id]) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                            </article>
                        @endforeach
                        @else
                        <p>Постов нет! Будьте первым!</p>
                        @endif
                    </div>
                </section>

                <div class="modal fade" tabindex="-1" role="dialog" id="edit-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Edit Post</h4>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label for="post-body">Edit the Post</label>
                                        <textarea class="form-control" name="post-body" id="post-body" rows="5"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="modal-save">Save changes</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div>
</div>
</div>

@endsection
