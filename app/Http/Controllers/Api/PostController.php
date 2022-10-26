<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Helper\Media;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use Media;

    public function getAllPost(Request $request)
    {
        $post = Post::where('user_id', Auth::user()->id)->where('title', 'LIKE', '%' . $request->query('search') . '%')->with('category')->paginate(8);
        $post->map(function ($item) {
            return $item->thumbnail_photo = Storage::url($item->thumbnail_photo);
        });
        return $this->responseSuccessWithData("Posts", $post);
    }

    public function getSinglePost($uuid)
    {
        $post = Post::where('uuid', $uuid)->first()->load('category');
        $post->thumbnail_photo = Storage::url($post->thumbnail_photo);
        return $this->responseSuccessWithData("Post", $post);
    }

    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'thumbnail_photo' => 'required|mimes:jpg,png,jpeg|max:10240',
            'first_paragraph' => 'required',
            'body' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $filePath = $this->uploads($request->file('thumbnail_photo'), "image/post");
        $validated = $validator->validated();
        $validated['thumbnail_photo'] = $filePath;
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['user_id'] = Auth::user()->id;

        try {
            Post::create($validated);

            return $this->responseCreated("Post berhasil ditambahkan");
        } catch (QueryException $e) {
            return $this->responseError($e->errorInfo, 500);
        }
    }

    public function updatePost(Request $request, $uuid)
    {
        $post = Post::where('uuid', $uuid)->first();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'thumbnail_photo' => 'nullable|mimes:jpg,png,jpeg|max:10240',
            'first_paragraph' => 'required',
            'body' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $filePath = $this->uploads($request->file('thumbnail_photo'), "image/post");

        if ($filePath) {
            Storage::delete($post->thumbnail_photo);
        }

        $validated = $validator->validated();
        $validated['thumbnail_photo'] = $filePath ?: $post->thumbnail_photo;
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        try {
            $post->update($validated);

            return $this->responseCreated("Post berhasil diubah");
        } catch (QueryException $e) {
            return $this->responseError($e->errorInfo, 500);
        }
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload' => 'required|mimes:png,jpg,gif|max:4096'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'message' => 'Foto harus berupa png, jpg atau gif dengan ukuran maksimal 4MB'
                ]
            ], 422);
        }

        $filePath = $this->uploads($request->file('upload'), "image/post");

        try {
            Photo::create([
                'user_id' => auth()->user()->id,
                'path' => $filePath
            ]);

            return response()->json([
                'url' => Storage::url($filePath)
            ], 200);
        } catch (\Throwable $th) {
            return $this->responseError('Internal Error', 500, $th->getMessage());
        }
    }

    public function deletePost(Post $post)
    {
        Storage::delete($post->thumbnail_photo);
        $post->delete();

        return $this->responseSuccess('Post berhasil dihapus');
    }
}
