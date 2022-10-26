<?php

namespace App\Helper;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait Media
{
    public function uploads($file, $path)
    {
        if ($file) {
            $fileName   = time() . '_' . $file->getClientOriginalName();
            Storage::put($path . '/' . $fileName, File::get($file));
            $filePath   = $path . '/' . $fileName;
            return $filePath;
        }

        return false;
    }
}
