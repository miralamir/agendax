<?php

namespace App\Http\Controllers;

use App\Models\Creador;
use App\Models\Evento;
use App\Models\Lugar;
use App\Models\Novedad;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Límite de URLs por sitemap que recomienda Google. Hoy el sitio ronda las
     * 600 URLs, así que entra holgado en un solo archivo. Si alguna vez se
     * superara, hay que partir en varios sitemaps + un <sitemapindex>: la
     * generación ya está aislada en urls(), que devuelve la lista completa.
     */
    private const MAX_URLS = 50000;

    /** El sitemap se rearma solo cada hora: el contenido nuevo entra sin intervención. */
    private const CACHE_MINUTOS = 60;

    /**
     * Secciones y sus subsecciones, tal como están declaradas en routes/web.php.
     * Se listan a mano porque son rutas fijas, no filas de una tabla.
     */
    private const SECCIONES = [
        'arte' => ['arte.creadores', 'arte.ferias', 'arte.novedades'],
        'musica' => ['musica.lanzamientos', 'musica.festivales', 'musica.novedades'],
        'cine' => ['cine.estrenos', 'cine.festivales-ciclos', 'cine.novedades'],
        'teatro' => ['teatro.cartelera', 'teatro.festivales', 'teatro.novedades'],
        'literatura' => ['literatura.novedades-editoriales', 'literatura.ferias', 'literatura.novedades'],
    ];

    public function index(): Response
    {
        $xml = Cache::remember('sitemap.xml', now()->addMinutes(self::CACHE_MINUTOS), function () {
            return $this->generarXml($this->urls());
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * Todas las URLs indexables del sitio.
     *
     * @return array<int, array{loc:string, lastmod:?string, priority:string, changefreq:string}>
     */
    private function urls(): array
    {
        $urls = [];

        // Home: se actualiza con lo último que se haya publicado, sea evento o
        // novedad. max() devuelve el string crudo de la base, así que se
        // normaliza a formato W3C.
        $ultimoEvento = Evento::where('isPublished', 1)->max('updated_at');
        $ultimaNovedad = Novedad::where('isPublished', 1)->max('updated_at');
        $ultima = collect([$ultimoEvento, $ultimaNovedad])->filter()->max();

        $urls[] = [
            'loc' => route('home'),
            'lastmod' => $ultima ? Carbon::parse($ultima)->toAtomString() : null,
            'priority' => '1.0',
            'changefreq' => 'daily',
        ];

        // Secciones y subsecciones
        foreach (self::SECCIONES as $seccion => $subsecciones) {
            $urls[] = [
                'loc' => route($seccion),
                'lastmod' => null,
                'priority' => '0.8',
                'changefreq' => 'daily',
            ];

            foreach ($subsecciones as $ruta) {
                $urls[] = [
                    'loc' => route($ruta),
                    'lastmod' => null,
                    'priority' => '0.7',
                    'changefreq' => 'weekly',
                ];
            }
        }

        // Eventos publicados. La URL es por id: /evento/{event} usa binding
        // implícito y Evento no redefine getRouteKeyName().
        Evento::where('isPublished', 1)
            ->orderByDesc('updated_at')
            ->chunk(500, function ($eventos) use (&$urls) {
                foreach ($eventos as $evento) {
                    $urls[] = [
                        'loc' => route('evento.show', $evento),
                        'lastmod' => $evento->updated_at?->toAtomString(),
                        'priority' => '0.7',
                        'changefreq' => 'weekly',
                    ];
                }
            });

        // Novedades publicadas (por slug)
        Novedad::where('isPublished', 1)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderByDesc('updated_at')
            ->chunk(500, function ($novedades) use (&$urls) {
                foreach ($novedades as $novedad) {
                    $urls[] = [
                        'loc' => route('novedades.show', $novedad->slug),
                        'lastmod' => $novedad->updated_at?->toAtomString(),
                        'priority' => '0.6',
                        'changefreq' => 'monthly',
                    ];
                }
            });

        // Perfiles públicos: creadores y lugares. No tienen flag de publicación,
        // pero sin slug la ruta no resuelve, así que esos quedan afuera.
        Creador::whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('id')
            ->chunk(500, function ($creadores) use (&$urls) {
                foreach ($creadores as $creador) {
                    $urls[] = [
                        'loc' => route('creador.show', $creador->slug),
                        'lastmod' => $creador->updated_at?->toAtomString(),
                        'priority' => '0.5',
                        'changefreq' => 'monthly',
                    ];
                }
            });

        Lugar::whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('id')
            ->chunk(500, function ($lugares) use (&$urls) {
                foreach ($lugares as $lugar) {
                    $urls[] = [
                        'loc' => route('lugar.show', $lugar->slug),
                        'lastmod' => $lugar->updated_at?->toAtomString(),
                        'priority' => '0.5',
                        'changefreq' => 'monthly',
                    ];
                }
            });

        return array_slice($urls, 0, self::MAX_URLS);
    }

    /** @param array<int, array<string, mixed>> $urls */
    private function generarXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $lastmod = $url['lastmod'] instanceof \DateTimeInterface
                ? $url['lastmod']->format(\DATE_ATOM)
                : $url['lastmod'];

            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . "</loc>\n";
            if ($lastmod) {
                $xml .= '    <lastmod>' . $lastmod . "</lastmod>\n";
            }
            $xml .= '    <changefreq>' . $url['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        return $xml . '</urlset>' . "\n";
    }
}
