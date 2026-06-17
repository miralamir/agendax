<?php

namespace App\Helpers;

class TextHelper
{
    /**
     * Convierte texto plano en HTML seguro, con URLs y emails clickeables
     * y saltos de linea como <br>. Escapa el HTML primero (seguridad).
     */
    public static function autoLink(?string $texto): string
    {
        if ($texto === null || $texto === '') return '';

        // 1. Escapar HTML primero (seguridad)
        $safe = e($texto);

        // 2. Linkear URLs (http:// o https://)
        $safe = preg_replace_callback(
            '#(https?://[^\s<]+[^\s<\.,:;"\')\]\!\?])#i',
            function ($m) {
                $url = $m[1];
                return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-words">' . $url . '</a>';
            },
            $safe
        );

        // 3. Linkear URLs que empiezan con www. (sin http)
        $safe = preg_replace_callback(
            '#(^|[\s>])(www\.[^\s<]+[^\s<\.,:;"\')\]\!\?])#i',
            function ($m) {
                $url = $m[2];
                return $m[1] . '<a href="https://' . $url . '" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-words">' . $url . '</a>';
            },
            $safe
        );

        // 4. Linkear emails
        $safe = preg_replace_callback(
            '#([a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,})#',
            function ($m) {
                return '<a href="mailto:' . $m[1] . '" class="text-blue-600 hover:underline">' . $m[1] . '</a>';
            },
            $safe
        );

        // 5. Saltos de linea como <br>
        return nl2br($safe);
    }
}
