<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $query = Post::orderByDesc('updated_at')->orderByDesc('created_at');

        if($filter){
            $value = $filter === 'published';
            $query->whereIsPublished($value);
        }

        $posts = $query->paginate(10)->withQueryString();

        $categories = Category::withCount('posts')->get();
        return view('admin.posts.index', compact('posts', 'filter', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $post = new Post();
        $categories = Category::select('label', 'id')->get();

        return view('admin.posts.create', compact('post', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|string|min:5|max:50|unique:posts',
            'content'=>'required|string',
            'image'=>'nullable|image|mimes:png,jpg,jpeg',
            'is_published'=>'nullable|boolean',
            'category_id' =>'nullable|exists:categories,id'
        ], [
            'title.required'=>'il titolo é obbligatorio',
            'title.min'=>'il titolo deve essere :min caratteri',
            'title.max'=>'il titolo deve essere :max caratteri',
            'title.unique'=>'non possono esistere due post con lo stesso titolo',
            'image.image'=>'il file inserito non eè un\'immagine',
            'image.mimes'=> 'Le estensioni valide sono .png, .jpg, .jpeg',
            'is_published.boolean' =>'il valore del campo pubblicazione non è valido',
            'content.required'=>'il contenuto è obbligatorio',
            'category_id.exists' => 'Categoria non valida o non esistente',
        ]);

        $data = $request->all();

        $post = new Post();

        
        $post->fill($data);
        $post->slug = Str::slug($post->title);
        $post->is_published = Arr::exists($data, 'is_published') ? true : false;


        
    
       // Controllo se mi arriva un file
        if (Arr::exists($data, 'image')) {
            $extension = $data['image']->extension();//'jpg' o 'png' o 'jpeg' ecc....
            // Lo salvo e prendo l'url
            $img_url = Storage::putFileAs('post_images', $data['image'], "$post->slug.$extension");
            $post->image = $img_url;
        };

        $post->save();

        return to_route('admin.posts.show', $post)->with('message', 'Post creato con successo')->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::select('label', 'id')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
{
    $request->validate([
        'title'=>['required','string','min:5','max:50','unique:posts', Rule::unique('posts')->ignore($post->id)],
        'content'=>'required|string',
        'image'=>'nullable|image|mimes:png,jpg,jpeg',
        'is_published'=>'nullable|boolean',
        'category_id' =>'nullable|exists:categories,id'
    ], [
        'title.required'=>'il titolo è obbligatorio',
        'title.min'=>'il titolo deve essere :min caratteri',
        'title.max'=>'il titolo deve essere :max caratteri',
        'title.unique'=>'non possono esistere due post con lo stesso titolo',
        'image.image'=>'il file inserito non è un\'immagine',
        'image.mimes'=> 'Le estensioni valide sono .png, .jpg, .jpeg',
        'is_published.boolean' =>'il valore del campo pubblicazione non è valido',
        'content.required'=>'il contenuto è obbligatorio',
        'category_id.exists' => 'Categoria non valida o non esistente',
    ]);

    $data = $request->all();

    $data['slug'] = Str::slug($data['title']);
    $data['is_published'] = Arr::exists($data, 'is_published') ? true : false;

    // Controllo se mi arriva una nuova immagine
    if ($request->hasFile('image')) {
        // Elimino l'immagine attuale se presente
        if ($post->image) {
            Storage::delete($post->image);
        }
        // Salvo la nuova immagine
        $extension = $data['image']->extension();
        $img_url = Storage::putFileAs('post_images', $data['image'], "{$data['slug']}.$extension");
        $data['image'] = $img_url;
    }

    $post->update($data);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return to_route('admin.posts.index')
        ->with('toast-button-type', 'danger')
        ->with('toast-message', 'Post eliminato con successo')
        ->with('toast-label', config('app.name'))
        ->with('toast-method','PATCH')
        ->with('toast-route', route('admin.posts.restore', $post->id))
        ->with('toast-button-label', 'Annulla');
    } 


    // rotte soft delete

        public function trash(){
            $posts = Post::onlyTrashed()->get();
            return view('admin.posts.trash', compact('posts'));
        }

        public function restore(Post $post ){
            
            $post->restore();
            return to_route('admin.posts.index')->with('type', 'success')->with('message', 'Post ripristinato con successo');
        }

        public function drop(Post $post){

            if($post->image) Storage::delete($post->image);
            $post->forceDelete();
            return to_route('admin.posts.trash')->with('type', 'warning')->with('message', 'Post eliminato definitivamente');
        }

        // Rotta pubblicazione
        public function togglePublication(Post $post){
            $post->is_published = !$post->is_published;
            $post->save();
            
        
            $action = $post->is_published ? 'pubblicato' : 'salvat come bozza';
            $type = $post->is_published ? 'success' : 'info';

            return back()->with('message', "il post $post->title è stato $action")->with('type', $type);
        }
}
