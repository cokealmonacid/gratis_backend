<?php

namespace App\Http;

use Storage;

class Helper
{
	private static $avatar_size = 200;

	private static $photo_size  = 400;

	public static function resizeImage($file, $avatar = false)
	{
		$size = $avatar ? self::$avatar_size : self::$photo_size;

		$resized = \Image::make($file)->resize($size, null, function($constraint){
			$constraint->aspectRatio();
		});

		return $resized;
	}

    public static function uploadImage($image_type, $name, $file)
    {
        $filename = md5($name);

        $dir = env('APP_ENV') . '/' . $image_type . '/' . $filename;

        $content = (string) $file->encode();

        $store = Storage::disk('spaces')->put($dir, $content, 'public');

        return [
            'url' => Storage::disk('spaces')->url($dir),
            'dir' => $dir
        ];
    }

    public static function deleteImage($filename)
    {
        Storage::disk('spaces')->delete($filename);
    }
}