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

    /**
     * Heuristica para distinguir contenido NUEVO (HTML real, generado por el editor
     * WYSIWYG) de contenido VIEJO (texto plano, de antes de este cambio). Quill envuelve
     * absolutamente todo en al menos un <p>, asi que cualquier contenido guardado por el
     * editor nuevo cumple esta condicion. No requiere backfill ni migracion de datos:
     * cada registro "migra" solo, la primera vez que se vuelve a guardar con el editor.
     */
    public static function looksLikeHtml(?string $texto): bool
    {
        if ($texto === null || trim($texto) === '') return false;
        return (bool) preg_match('/<\s*(p|br|strong|em|u|ol|ul|li|a)\b/i', $texto);
    }

    /**
     * Convierte un valor guardado (viejo texto plano O nuevo HTML) al HTML inicial
     * que hay que precargar dentro del editor WYSIWYG (Quill) al abrir un form de edicion.
     * - Si ya es HTML (looksLikeHtml) se devuelve tal cual, Quill lo interpreta directo.
     * - Si es texto plano legacy: escapa entidades y convierte saltos de linea a <br>,
     *   sin dejar el "\n" original (evita que el navegador lo colapse en un espacio extra).
     */
    public static function toEditableHtml(?string $texto): string
    {
        if ($texto === null || $texto === '') return '';
        if (self::looksLikeHtml($texto)) return $texto;
        return str_replace(["\r\n", "\n", "\r"], '<br>', e($texto));
    }

    /**
     * Linkea URLs/emails sueltos dentro de HTML ya existente, sin tocar texto que ya
     * esta dentro de un <a> (evita doble-link) ni alterar el resto del marcado.
     * Pensado para correr UNA SOLA VEZ al guardar contenido del editor WYSIWYG,
     * despues de purificar el HTML (Purifier::clean).
     */
    public static function autoLinkHtml(string $html): string
    {
        if (trim($html) === '') return $html;
        if (!preg_match('/https?:\/\/|www\.|@/i', $html)) return $html;

        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="UTF-8"?><div>' . $html . '</div>',
            LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $root = $dom->documentElement;
        if (!$root) return $html;

        self::linkifyChildren($dom, $root);

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }
        return $result;
    }

    private static function linkifyChildren(\DOMDocument $dom, \DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                self::linkifyTextNode($dom, $child);
            } elseif ($child->nodeType === XML_ELEMENT_NODE && strtolower($child->nodeName) !== 'a') {
                self::linkifyChildren($dom, $child);
            }
            // Si es <a>, no se recorre adentro: se deja intacto (evita doble-link).
        }
    }

    private static function linkifyTextNode(\DOMDocument $dom, \DOMText $textNode): void
    {
        $text = $textNode->nodeValue;

        $pattern = '#(https?://[^\s<]+[^\s<\.,:;"\')\]\!\?])|(?<![a-zA-Z0-9])(www\.[^\s<]+[^\s<\.,:;"\')\]\!\?])|([a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,})#i';

        if (!preg_match($pattern, $text)) return;

        preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

        $fragment = $dom->createDocumentFragment();
        $lastIndex = 0;

        foreach ($matches as $m) {
            $full = $m[0][0];
            $offset = $m[0][1];

            if ($offset > $lastIndex) {
                // substr (no mb_substr): PREG_OFFSET_CAPTURE devuelve offsets en bytes,
                // y el patron nunca corta a mitad de un caracter multibyte (los delimitadores
                // que excluye son todos ASCII), asi que substr por bytes es seguro y consistente.
                $fragment->appendChild($dom->createTextNode(substr($text, $lastIndex, $offset - $lastIndex)));
            }

            $anchor = $dom->createElement('a');
            if (!empty($m[1][0])) {
                $url = $m[1][0];
                $anchor->setAttribute('href', $url);
                $anchor->appendChild($dom->createTextNode($url));
            } elseif (!empty($m[2][0])) {
                $url = $m[2][0];
                $anchor->setAttribute('href', 'https://' . $url);
                $anchor->appendChild($dom->createTextNode($url));
            } else {
                $email = $m[3][0];
                $anchor->setAttribute('href', 'mailto:' . $email);
                $anchor->appendChild($dom->createTextNode($email));
            }
            $anchor->setAttribute('target', '_blank');
            $anchor->setAttribute('rel', 'noopener noreferrer');
            $anchor->setAttribute('class', 'text-blue-600 hover:underline break-words');

            $fragment->appendChild($anchor);
            $lastIndex = $offset + strlen($full);
        }

        if ($lastIndex < strlen($text)) {
            $fragment->appendChild($dom->createTextNode(substr($text, $lastIndex)));
        }

        $textNode->parentNode->replaceChild($fragment, $textNode);
    }
}
