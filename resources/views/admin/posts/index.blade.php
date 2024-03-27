@extends('layouts.app')

  @section('title', 'Posts')

  @section('content')
   
    <header class="d-flex align-items-center justify-content-between mt-3">
        <h1>Posts</h1>

        <!-- Filtro -->
        <form action="{{ route('admin.posts.index') }}" method="GET">
        <div class="input-group">
          <select class="form-select" name="filter">
              <option value="" >Tutti</option>
              <option value="published" @if($filter === 'published') selected @endif>Pubblicati</option>
              <option value="drafts" @if($filter === 'drafts') selected @endif>Bozze</option>
            </select>
            <button class="bn btn-outline-secondary">Go</button>
        </div>  
        </form>
    </header>
    
    <!-- Table -->
  <table class="table table-dark table-striped mt-5">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Title</th>
      <th scope="col">Slug</th>
      <th scope="col">Categoria</th>
      <th scope="col">Stato</th>
      <th scope="col">Creato il</th>
      <th scope="col">Modificato il</th>
      <th>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.posts.trash') }}" class="btn btn-sm btn-secondary">Vedi cestino</a>

            <a href="{{route('admin.posts.create')}}" class="btn btn-sm btn-success">
            <i class="fas fa-plus"> Nuovo</i>
          </a>
        </div>
      </th>
    </tr>
  </thead>
  <tbody>
    @forelse($posts as $post)
    <tr>
      <th scope="row">{{ $post->id }}</th>
      <td>{{ $post->title }}</td>
      <td>{{ $post->slug }}</td>
      <td>
    @if($post->category)
        <span class="badge" style="background-color: {{ $post->category->colors }}">{{ $post->category->label }}</span>
    @else
        Nessuna categoria associata
    @endif
</td>      <td>
        <form action="{{route('admin.posts.publish', $post->id)}}" method="POST" class="{{ 'is_published' . $post->id }}">
          @csrf
          @method('PATCH')
          
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="button" id="{{ 'is_published' . $post->id }}"
                @if ($post->is_published) checked @endif>
              <label class="form-check-label" for="{{ 'is_published' . $post->id }}">
               {{ $post->is_published ? 'Pubblicato' : 'Bozza' }}
              </label>
            </div>
          </form>
        <!-- <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="button" id="is_published" 
           @if ($post->is_published) checked @endif>
          <label class="form-check-input" 
           for="is_published">{{ $post->is_published ? 'Pubblicato' : 'Bozza'}}</label>
        </div> -->
      </td>
      
      
      <td>{{ $post->getFormaterDate('created_at', 'd-m-Y H:i:s') }}</td>
      <td>{{ $post->getFormaterDate('updated_at') }}</td>
      <td>
        <div class="d-flex justify-content-end gap-3">
            <a href="{{ route('admin.posts.show', $post)}}" class="btn btn-sm btn-primary">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('admin.posts.edit', $post)}}" class="btn btn-sm btn-warning"> 
              <i class="fas fa-pencil"></i>
            </a>
        
        <form action="{{ route('admin.posts.destroy', $post->id)}}" method="POST" class="delete-form"
        data-bs-toggle="modal" data-bs-target="#modal">
            @csrf
            @method('DELETE')        
            <button type="submit" class="btn btn-sm btn-danger">
              <i class="fas fa-trash-can"></i>
            </button>
        </form>
        </div>
      </div>
      </td>
    </tr>

    @empty
    <tr>
        <td colspan="8">
            <h3 class="text-center">Non ci sono post</h3>
        </td>
    </tr>
    @endforelse
    
   
  </tbody>
</table>


<!-- Pagination -->
<!-- questa generava errore  ora non piu Boooooooooooooooooo-->
  @if($posts->hasPages())
    {{$posts->links()}}
  @endif
@endsection
<section class="my-3" id="categories-post">
  <h3 class="mb-3">Post per categoria</h3>
  <div class="row row-cols-3">
    @foreach($categories as $category)
    <div class="col">
        <h4 class="my-3"> {{$category->label}} ({{ $category->posts_count }})</h4>
      @forelse($category->posts as $post)
        <p>{{ $post->title}}</p>
      @empty
        <p>nessun post</p>
      @endforelse
      </div>
    @endforeach
  </div>

</section>


@section('scripts')
  @vite('resources/js/delete_confirmation.js')
@endsection
