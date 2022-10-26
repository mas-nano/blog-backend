<?php

namespace App\Http\Controllers\Client;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['category', 'user'])->where('title', 'LIKE', '%' . $request->query('search') . '%')->paginate(9)->through(function ($item) {
            $item->thumbnail_photo = Storage::url($item->thumbnail_photo);
            return $item;
        });
        return view('home.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->with(['category', 'user'])->get();
        return view('post.index', compact('post'));
    }
}
