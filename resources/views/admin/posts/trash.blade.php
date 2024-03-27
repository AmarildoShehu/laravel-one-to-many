@extends('layouts.app')

  @section('title', 'Posts')

  @section('content')
   
    <header class="d-flex align-items-center justify-content-between">
        <h1>Post Eliminati</h1>

        <a href="{{ route('admin.posts.index') }}">Vedi post attivi</a>

    </header>
    
    <!-- Table -->
  <table class="table table-dark table-striped mt-5">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Title</th>
      <th scope="col">Slug</th>
      <th scope="col">Stato</th>
      <th scope="col">Creato il</th>
      <th scope="col">Modificato il</th>
      <th>
        <div class="d-flex justify-content-end">
            <a class="btn btn-sm btn-danger">
        <!-- #TODO -->
            <i class="fas fa-trash"> Svuota cestino</i>
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
      <td>{{ $post->is_published ? 'Pubblicato' : 'Bozza'}}</td>
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
        
        <form action="{{ route('admin.posts.drop', $post->id)}}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')        
            <button type="submit" class="btn btn-sm btn-danger">
              <i class="fas fa-trash-can"></i>
            </button>
        </form>

        <form action="{{ route('admin.posts.restore', $post->id)}}" method="POST">
            @csrf
            @method('PATCH')        
            <button type="submit" class="btn btn-sm btn-success">
              <i class="fas fa-arrows-rotate"></i>
            </button>
        </form>

        </div>
      </div>
      </td>
    </tr>

    @empty
    <tr>
        <td colspan="7">
            <h3 class="text-center">Non ci sono post</h3>
        </td>
    </tr>
    @endforelse
    
   
  </tbody>
</table>


<!-- questa generava errore  ora non piu Boooooooooooooooooo-->
 
@endsection

@section('scripts')
  @vite('resources/js/delete_confirmation.js')
@endsection
