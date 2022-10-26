<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::where('user_id', auth()->user()->id)->get();
        foreach ($photos as $photo) {
            $photo->path = Storage::url($photo->path);
        }

        return $this->responseSuccessWithData('Photos', $photos);
    }

    public function delete(Photo $photo)
    {
        Storage::delete($photo->path);
        $photo->delete();

        return $this->responseSuccess('Berhasil dihapus');
    }
}
