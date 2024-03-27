@extends('layouts.app')

@section('title', 'Posts')

@section('content')

<header>
    <h1 class="mt-4 mb-1"> {{ $post->title }}</h1>
    <p>Categoria: @if ($post->category)
        <span classe="badge" style="background-color: {{ $post->category->colors}}" $post->>{{ $post->category->label }}</span>
        @else
            Nessuna
        @endif
    </p>
</header>

<div class="clearfix">
    @if ($post->image)
        <img src="{{ $post->printImage() }}" alt="{{ $post->title }}" class="me-2 float-start">
    @endif
    <p>{{ $post->content }}</p>
    <div>
        <strong>Created at:</strong> {{ $post->getFormaterDate('created_at', 'd-m-Y H:i:s')}}
        <strong>Updated at:</strong> {{ $post->getFormaterDate('updated_at', 'd-m-Y H:i:s') }}
    </div>
</div>

<footer class="mt-4 mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Go back</a>

    <div class="d-flex justify-content-between gap-3">
        <a href="{{ route('admin.posts.edit', $post)}}" class="btn btn-warning"> <i class="fas fa-pencil"></i> Modifica</a>
        
        <form action="{{ route('admin.posts.destroy', $post->id)}}" 
        method="POST" class="delete-form"
        data-bs-toggle="modal" data-bs-target="#modal">
            @csrf
            @method('DELETE')        
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-can me-2"></i>Elimina</button>
        </form>
    
    </div>

</footer>

@endsection

@section('scripts')
  @vite('resources/js/delete_confirmation.js')
@endsection
