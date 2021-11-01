<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait FileSave {

    /**
     * @param $path
     * @param $file
     * @return string
     */
    public static function getUrl($path, $file): string
    {
        $storagePath = Storage::putFile($path, $file);
        return Storage::url($storagePath);
    }
}
