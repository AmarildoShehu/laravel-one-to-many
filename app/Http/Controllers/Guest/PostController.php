<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {

        $post = Post::whereSlug($slug)->first();

        if (!$post) abort(404);
        
        return view('guest.posts.show', compact('post'));
    }

}
