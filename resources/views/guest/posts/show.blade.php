@extends('layouts.app')

@section('title', 'Post')

@section('content')
<div class="card my-5" >
        <div class="card-header d-flex aligh-items-center justify-content-between">
            {{$post->title}}

            <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-sm btn-primary">Vedi</a>
        </div>
        <div class="card-body">
            <div class="row">
                @if($post->image)
                <div class="col-3">
                    <img src="{{ $post->printImage() }}" alt="{{ $post->title}}">
                </div>
                @endif
                
                <div class="col">
    
                    <h5 class="card-title mb-3">{{ $post->title}}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $post->created_at }}</h6>
                    <p class="card-text">{{ $post->content }}</p>
                    
                </div>
            </div>

        </div>
    </div>
@endsection