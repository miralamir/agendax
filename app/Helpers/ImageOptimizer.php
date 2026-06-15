<?php
namespace App\Helpers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    public static function store(UploadedFile $file, string $path, int $maxWidth = 1920, int $quality = 85): string
    {
        $filename = Str::random(40) . '.jpg';
        $fullPath = $path . '/' . $filename;

        $manager = new ImageManager(new Driver());
        $encoded = $manager->decodePath($file->getRealPath())
            ->scaleDown(width: $maxWidth, height: $maxWidth)
            ->encode(new JpegEncoder($quality));

        Storage::disk('public')->put($fullPath, $encoded);

        return $fullPath;
    }
}
