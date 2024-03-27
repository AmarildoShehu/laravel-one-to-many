@if($post->exists)
    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" novalidate>
    @method('PUT')
@else
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" novalidate>
@endif

@csrf
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="title" class="form-label">Titolo</label>
                    <input type="text" 
                    class="form-control @error('title') is-invalid @elseif(old('title','')) is-valid @enderror" id="title" name="title" placeholder="Titolo..." 
                    value="{{ old('title', $post->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                    @else
                        <div class="form.text">
                            Inserisci il titolo del post
                        </div>
                    @enderror
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" 
                    class="form-control" 
                    id="slug" value="{{ Str::slug(old('title', $post->title))}}" disabled>
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label for="content" class="form-label">Contenuto del post</label>
                    <textarea class="form-control
                    @error('content') is-invalid @elseif(old('content','')) is-valid @enderror"
                    id="content" rows="10" name="content" 
                    required>{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                    @else
                        <div class="form.text">
                            Inserisci il contenuto del post
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-5">
                <div class="mb-3">
                <label for="category_id" class="form-label">Seleziona categoria</label>
                    <select name="category_id" id="category_id" class="form-select"
                    @error('category_id') is-invalid @elseif(old('category_id','')) is-valid @enderror>
                        <option value="">Nessuna</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id}}" @if (old('category_id', $post->category?->id) == $category->id) selected @endif >{{ $category->label}}</option>
                            
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-5">
                <div class="mb-3">
                    <label for="image" class="form-label">Immagine</label>

                    <div id="previous-image-field" class="input-group @if(!$post->image) d-none @endif">
                        <button class="btn btn-outline-secondary" 
                        type="button"
                        id="change-image-button">
                            Cambia immagine
                        </button>
                        <input type="text"
                        class="form-control"
                        value="{{ old('image', $post->image) }}"
                        disabled>
                    </div>

                    <input type="file" 
                    class="form-control @if($post->image) d-none @endif 
                    @error('image') is-invalid @elseif(old('image','')) is-valid @enderror"
                    id="image" name="image" placeholder="http:// o https://" 
                    >

                </div>
                    @error('image')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                    @else
                        <div class="form.text">
                            Carica un file immagine
                        </div>
                    @enderror
            </div>
            <div class="col-1">
                <img src="{{ old('image', $post->image 
                    ? $post->printImage()
                    : 'https://') }}" 
                    class="img-fluid" alt="{{ $post->image ? $post->title : 'preview'}}" 
                    id="preview">
            </div>
            <div class="col-12 d-flex justify-content-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1"   
                        @if (old('is_published', $post->is_published)) checked @endif >
                    <label class="form-check-label" for="is_published">
                        Pubblicato
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Torna alla lista</a>
            <div class="d-flex align-items-center gap-2">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-eraser"></i>
                    Svuota i campi
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-floppy-disk"></i>
                    Salva
                </button>
            </div>
        </div>
    </form>
