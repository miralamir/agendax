<?php
namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Optimiza una imagen y la guarda en el disco 'public'.
     * Usa Imagick (no GD) para gestión de color: normaliza a sRGB — si la imagen
     * trae un perfil ICC embebido (ECI-RGB, Display P3, Adobe RGB...), transforma
     * los píxeles a sRGB para que los navegadores la muestren fiel al original.
     * Mantiene el pipeline: scaleDown a maxWidth (sin agrandar) + JPEG quality.
     */
    public static function store(UploadedFile $file, string $path, int $maxWidth = 1920, int $quality = 85): string
    {
        $filename = Str::random(40) . '.jpg';
        $fullPath = $path . '/' . $filename;

        $img = new \Imagick($file->getRealPath());

        // Gestión de color -> sRGB (fiel + universal)
        if (!empty($img->getImageProfiles('icc', true))) {
            // Transforma desde el perfil embebido al sRGB bundleado en el repo.
            $img->profileImage('icc', file_get_contents(resource_path('icc/sRGB.icc')));
        }
        $img->transformImageColorspace(\Imagick::COLORSPACE_SRGB);

        // scaleDown: reducir a maxWidth x maxWidth respetando proporción, SIN agrandar.
        if ($img->getImageWidth() > $maxWidth || $img->getImageHeight() > $maxWidth) {
            $img->resizeImage($maxWidth, $maxWidth, \Imagick::FILTER_LANCZOS, 1, true); // bestfit
        }

        $img->setImageFormat('jpeg');
        $img->setImageCompressionQuality($quality);
        $img->stripImage(); // ya está en sRGB; el navegador lo asume -> correcto y liviano

        $blob = $img->getImageBlob();
        $img->clear();
        $img->destroy();

        $ok = Storage::disk('public')->put($fullPath, $blob);
        if ($ok === false) {
            throw new \RuntimeException("ImageOptimizer: no se pudo escribir la imagen en {$fullPath}");
        }

        return $fullPath;
    }
}
