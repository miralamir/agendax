<?php
// bootstrap.php
// Carga el autoloader de Composer
require __DIR__.'/vendor/autoload.php';

// Crea la aplicación Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Arranca el kernel HTTP
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// --- Ahora podemos usar los modelos de Laravel ---
use App\Models\Evento;
use Illuminate\Support\Carbon;

try {
    echo "Iniciando la creación del evento...\n";
    
    $evento = new Evento();
    $evento->title = "Nuevas Muestras en el Museo Quinquela Martín: Pérez Esquivel, Ferrari y Pisani";
    $evento->category = "Artes Visuales";
    $evento->singleDate = Carbon::createFromFormat('Y-m-d H:i', "2026-06-06 14:00");
    $evento->locationName = "Museo Benito Quinquela Martín";
    $evento->locationAddress = "Av. Pedro de Mendoza 1835, La Boca, Buenos Aires";
    $evento->cost = "General: \$10.000 | Residentes: \$2.000 | Miércoles GRATIS.";
    $evento->mainImageUrl = "https://upload.wikimedia.org/wikipedia/commons/e/e0/Museo_Quinquela_Mart%C3%ADn.JPG";
    $evento->description = "El icónico museo de La Boca inaugura tres exhibiciones simultáneas con obras de Adolfo Pérez Esquivel, José “Pipo” Ferrari y Sergio Pisani.";
    $evento->is_featured = 1;
    $evento->is_published = 1;
    $evento->user_id = 1; // ID de usuario por defecto

    $evento->save();

    echo "¡Éxito! Evento '{$evento->title}' creado con ID: {$evento->id}.\n";

} catch (\Exception $e) {
    echo "Error al crear el evento: " . $e->getMessage() . "\n";
}
