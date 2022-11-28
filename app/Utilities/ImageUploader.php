<?php
namespace App\Utilities;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageUploader
{
    public static function upload($image , $patch , $diskType = 'public_storage')
    {
        Storage::disk($diskType)->put($patch, File::get($image));
    }

    public static function uploadMany(array $images , $patch , $diskType = 'public_storage')
    {
        $imagesPatch = [];
        foreach ($images as $key => $image)
        {
            $fullPatch = $patch . $key . '_' . $image->getClientOriginalName();

            self::upload($image,$fullPatch,$diskType);

            $imagesPatch += [$key => $fullPatch];
        }
        return $imagesPatch;
    }
}
