<?php
namespace App\Utilities;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageUploader
{
    // for source
    public static function upload($image , $patch , $diskType = 'public_storage')
    {
        if (!is_null($image))
            Storage::disk($diskType)->put($patch, File::get($image));
    }

    // for demo & thumbnail
    public static function uploadMany(array $images , $patch , $diskType = 'public_storage')
    {
        $imagesPatch = [];
        foreach ($images as $key => $image)
        {
            $fullPatch = $patch . $key . '_' . $image->getClientOriginalName();

            self::upload($image, $fullPatch, $diskType);

            $imagesPatch += [$key => $fullPatch];
        }
        return $imagesPatch;
    }
}
