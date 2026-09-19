
<?php
/**
 * Recuperar reseñas de Google Maps a partir de una URL SIN Place ID.
 *
 * Idea clave: la URL contiene el "feature ID" (0x4eaf0239cc97e119:0x777b4711a8f74a1e).
 * Un Place ID moderno (ChIJ...) es simplemente ese par de números codificado en
 * base64, así que se puede reconstruir SIN buscar nada en la API.
 *
 * Flujo:
 *   1. Se reconstruye el Place ID a partir del feature ID de la URL.
 *   2. Place Details (Places API New) -> se piden las reseñas.
 *   3. Si el Place ID reconstruido no funciona, se intenta Text Search como respaldo.
 *
 * Requisitos:
 *   - PHP 7.4+ con la extensión cURL.
 *   - Proyecto en Google Cloud con "Places API (New)" habilitada,
 *     facturación activa y una API key.
 *
 * Uso:
 *   Navegador:  resenas_google_maps.php
 *   Terminal:   php resenas_google_maps.php
 */

const API_KEY = 'AIzaSyCRw-m6FwodZdcIPw1rtAKWqvyziRm1ihM';

// Opcional: si ya conoces el Place ID (ChIJ...), pégalo aquí y tendrá prioridad.
const PLACE_ID_MANUAL = 'ChIJGeGXzDkCr04RHkr3qBFHe3c';

// Acepta la URL larga o el enlace corto (maps.app.goo.gl/...)
const MAPS_URL = 'https://www.google.com/maps/place/Robinsons+Rentals/@34.4573987,-117.7144102,84670m/data=!3m1!1e3!4m10!1m2!2m1!1sRobinsons+Rentals!3m6!1s0x4eaf0239cc97e119:0x777b4711a8f74a1e!8m2!3d34.4820709!4d-117.3703549!15sChFSb2JpbnNvbnMgUmVudGFscyIDiAEBWhMiEXJvYmluc29ucyByZW50YWxzkgEecGFydHlfZXF1aXBtZW50X3JlbnRhbF9zZXJ2aWNlmgFEQ2k5RFFVbFJRVU52WkVOb2RIbGpSamx2VDI1R01HUkhXa05oTVZVMFdUSTBkRTF0VG1GYWVUQTFWVVpLY2xReVl4QULgAQD6AQQIABBD!16s%2Fg%2F11ny0gdl0d?entry=ttu&g_ep=EgoyMDI2MDkxNi4wIKXMDSoASAFQAw%3D%3D';

/* ------------------------------------------------------------------ */
/* 0. Resolver enlaces cortos (maps.app.goo.gl)                        */
/* ------------------------------------------------------------------ */

/**
 * Si la URL es un enlace corto, sigue las redirecciones a mano y devuelve la
 * URL larga de Google Maps. Si ya es una URL larga, la devuelve sin cambios.
 */
function resolverUrl(string $url): string
{
    $host = parse_url($url, PHP_URL_HOST) ?: '';
    if (!in_array($host, ['maps.app.goo.gl', 'goo.gl'], true)) {
        return $url;
    }

    for ($i = 0; $i < 5; $i++) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false, // las redirecciones se siguen a mano
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
        ]);
        curl_exec($ch);
        $siguiente = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        curl_close($ch);

        if (!$siguiente) {
            break; // ya no hay más redirecciones
        }
        $url = $siguiente;

        // En cuanto la URL trae el feature ID (0x...:0x...) no hace falta seguir
        if (preg_match('/0x[0-9a-f]+:0x[0-9a-f]+/i', urldecode($url))) {
            break;
        }
    }

    return $url;
}

/* ------------------------------------------------------------------ */
/* 1. Extraer datos de la URL y reconstruir el Place ID                */
/* ------------------------------------------------------------------ */

function parsearUrlMaps(string $url): array
{
    $datos = [
        'nombre' => null, 'lat' => null, 'lng' => null,
        'cid' => null, 'placeIdDerivado' => null,
    ];

    // Nombre: /place/Robinsons+Rentals/
    if (preg_match('#/place/([^/@]+)#', $url, $m)) {
        $datos['nombre'] = urldecode(str_replace('+', ' ', $m[1]));
    }

    // Coordenadas exactas del negocio: !3d<lat>!4d<lng>
    if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $m)) {
        $datos['lat'] = (float) $m[1];
        $datos['lng'] = (float) $m[2];
    }

    // Feature ID: 0x<hex1>:0x<hex2> (aparece como !1s0x..., ftid=0x..., etc.)
    if (preg_match('/0x([0-9a-f]+):0x([0-9a-f]+)/i', $url, $m)) {
        $datos['cid']             = hexToDec($m[2]);               // 2ª mitad en decimal = CID
        $datos['placeIdDerivado'] = placeIdDesdeFid($m[1], $m[2]); // ChIJ...
    }

    return $datos;
}

/**
 * Reconstruye el Place ID (ChIJ...) desde las dos mitades hexadecimales del
 * feature ID. Estructura: 0x0A 0x12 0x09 <hex1 little-endian> 0x11 <hex2 little-endian>,
 * codificado en base64 "url-safe" y sin relleno "=".
 */
function placeIdDesdeFid(string $hex1, string $hex2): string
{
    $littleEndian = function (string $hex): string {
        $hex = str_pad(strtolower($hex), 16, '0', STR_PAD_LEFT);
        return hex2bin(implode('', array_reverse(str_split($hex, 2))));
    };

    $raw = "\x0a\x12\x09" . $littleEndian($hex1) . "\x11" . $littleEndian($hex2);

    return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
}

/** Convierte hex a decimal (string) sin perder precisión. */
function hexToDec(string $hex): string
{
    if (function_exists('gmp_init')) {
        return gmp_strval(gmp_init($hex, 16), 10);
    }
    if (function_exists('bcadd')) {
        $dec = '0';
        foreach (str_split(strtolower($hex)) as $c) {
            $dec = bcadd(bcmul($dec, '16'), (string) hexdec($c));
        }
        return $dec;
    }
    return (string) hexdec($hex);
}

/* ------------------------------------------------------------------ */
/* 2. Cliente HTTP mínimo                                              */
/* ------------------------------------------------------------------ */

function llamarApi(string $metodo, string $url, string $fieldMask, ?array $cuerpo = null): array
{
    $headers = [
        'Content-Type: application/json',
        'X-Goog-Api-Key: ' . API_KEY,
        'X-Goog-FieldMask: ' . $fieldMask,
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 20,
    ]);

    if ($metodo === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cuerpo, JSON_UNESCAPED_UNICODE));
    }

    $respuesta = curl_exec($ch);
    if ($respuesta === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException("Error de red: $error", 0);
    }
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $json = json_decode($respuesta, true);
    if ($http >= 400) {
        $msg = $json['error']['message'] ?? $respuesta;
        // El código HTTP viaja como código de la excepción
        throw new RuntimeException("La API respondió HTTP $http: $msg", $http);
    }

    return $json ?? [];
}

/* ------------------------------------------------------------------ */
/* 3. Respaldo: buscar el Place ID con Text Search                     */
/* ------------------------------------------------------------------ */

function buscarPlaceId(array $datos, array &$log): ?array
{
    if (empty($datos['nombre'])) {
        $log[] = 'La URL no contiene el nombre del negocio; no se puede usar Text Search.';
        return null;
    }

    $cuerpo = [
        'textQuery'    => $datos['nombre'],
        'languageCode' => 'es',
        'pageSize'     => 5,
    ];

    if ($datos['lat'] !== null && $datos['lng'] !== null) {
        $cuerpo['locationBias'] = [
            'circle' => [
                'center' => ['latitude' => $datos['lat'], 'longitude' => $datos['lng']],
                'radius' => 5000.0,
            ],
        ];
    }

    $resp = llamarApi(
        'POST',
        'https://places.googleapis.com/v1/places:searchText',
        'places.id,places.displayName,places.formattedAddress,places.googleMapsUri',
        $cuerpo
    );

    $lugares = $resp['places'] ?? [];
    if (!$lugares) {
        $log[] = 'Text Search no devolvió lugares. Respuesta cruda: ' . json_encode($resp);
        return null;
    }

    // Confirmar por CID (googleMapsUri suele ser https://maps.google.com/?cid=...)
    if ($datos['cid']) {
        foreach ($lugares as $lugar) {
            if (isset($lugar['googleMapsUri']) && strpos($lugar['googleMapsUri'], 'cid=' . $datos['cid']) !== false) {
                return $lugar;
            }
        }
        $log[] = 'Ningún resultado de Text Search coincide con el CID ' . $datos['cid'] . '; se usa el primero.';
    }

    return $lugares[0];
}

/* ------------------------------------------------------------------ */
/* 4. Obtener las reseñas (Place Details)                              */
/* ------------------------------------------------------------------ */

function obtenerResenas(string $placeId): array
{
    return llamarApi(
        'GET',
        'https://places.googleapis.com/v1/places/' . rawurlencode($placeId) . '?languageCode=es',
        'id,displayName,rating,userRatingCount,googleMapsUri,reviews'
    );
}

/* ------------------------------------------------------------------ */
/* 5. Ejecución                                                        */
/* ------------------------------------------------------------------ */

$esCli = (PHP_SAPI === 'cli');
$h = fn(?string $s): string => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

$log     = [];
$detalle = null;
$placeId = null;
$origen  = '';

try {
    $urlLarga = resolverUrl(MAPS_URL);
    $log[] = 'URL resuelta: ' . $urlLarga;
    $datos = parsearUrlMaps($urlLarga);

    // Candidatos en orden de prioridad
    $candidatos = [];
    if (PLACE_ID_MANUAL !== '') {
        $candidatos[] = ['manual', PLACE_ID_MANUAL];
    }
    if ($datos['placeIdDerivado']) {
        $candidatos[] = ['reconstruido desde la URL', $datos['placeIdDerivado']];
    }

    foreach ($candidatos as [$nombreOrigen, $id]) {
        try {
            $detalle = obtenerResenas($id);
            $placeId = $id;
            $origen  = $nombreOrigen;
            break;
        } catch (RuntimeException $e) {
            // Errores de credenciales, cuota o red: no tiene sentido seguir probando
            if (!in_array($e->getCode(), [400, 404], true)) {
                throw $e;
            }
            $log[] = "Place ID $nombreOrigen ($id) falló: " . $e->getMessage();
        }
    }

    // Respaldo: búsqueda de texto
    if ($detalle === null) {
        $lugar = buscarPlaceId($datos, $log);
        if ($lugar === null) {
            throw new RuntimeException('No se pudo obtener el negocio ni por Place ID ni por búsqueda. Pega el Place ID en PLACE_ID_MANUAL.');
        }
        $placeId = $lugar['id'];
        $detalle = obtenerResenas($placeId);
        $origen  = 'búsqueda de texto';
    }
} catch (Throwable $e) {
    if (!$esCli) {
        header('Content-Type: text/plain; charset=utf-8');
    }
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    if (isset($datos['placeIdDerivado'])) {
        echo 'Place ID reconstruido: ' . $datos['placeIdDerivado'] . PHP_EOL;
    }
    foreach ($log as $linea) {
        echo ' - ' . $linea . PHP_EOL;
    }
    exit(1);
}

$nombre  = $detalle['displayName']['text'] ?? $datos['nombre'];
$resenas = $detalle['reviews'] ?? [];

if ($esCli) {
    echo "Negocio:  $nombre\n";
    echo "Place ID: $placeId ($origen)\n";
    echo 'Rating:   ' . ($detalle['rating'] ?? 'n/d') . ' (' . ($detalle['userRatingCount'] ?? 0) . " reseñas en total)\n";
    echo 'Reseñas devueltas por la API: ' . count($resenas) . "\n\n";
    foreach ($resenas as $r) {
        echo '★ ' . ($r['rating'] ?? '?') . ' - ' . ($r['authorAttribution']['displayName'] ?? 'Anónimo')
            . ' (' . ($r['relativePublishTimeDescription'] ?? '') . ")\n";
        echo '  ' . ($r['text']['text'] ?? '(sin texto)') . "\n\n";
    }
    exit(0);
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Reseñas de <?= $h($nombre) ?></title>
<style>
  body { font-family: system-ui, sans-serif; max-width: 760px; margin: 2rem auto; padding: 0 1rem; color: #222; }
  .resena { border: 1px solid #ddd; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
  .autor { display: flex; align-items: center; gap: .6rem; font-weight: 600; }
  .autor img { width: 36px; height: 36px; border-radius: 50%; }
  .estrellas { color: #f5a623; }
  .fecha { color: #777; font-size: .85rem; }
  small { color: #777; }
</style>
</head>
<body>
  <h1><?= $h($nombre) ?></h1>
  <p>
    <span class="estrellas">★</span> <?= $h((string) ($detalle['rating'] ?? 'n/d')) ?>
    · <?= (int) ($detalle['userRatingCount'] ?? 0) ?> reseñas en total
    <br><small>Place ID: <?= $h($placeId) ?> (<?= $h($origen) ?>)</small>
  </p>

  <?php if (!$resenas): ?>
    <p>La API no devolvió reseñas.</p>
  <?php endif; ?>

  <?php foreach ($resenas as $r): ?>
    <div class="resena">
      <div class="autor">
        <?php if (!empty($r['authorAttribution']['photoUri'])): ?>
          <img src="<?= $h($r['authorAttribution']['photoUri']) ?>" alt="">
        <?php endif; ?>
        <a href="<?= $h($r['authorAttribution']['uri'] ?? '#') ?>" target="_blank" rel="noopener">
          <?= $h($r['authorAttribution']['displayName'] ?? 'Anónimo') ?>
        </a>
      </div>
      <div>
        <span class="estrellas"><?= str_repeat('★', (int) ($r['rating'] ?? 0)) ?><?= str_repeat('☆', 5 - (int) ($r['rating'] ?? 0)) ?></span>
        <span class="fecha"><?= $h($r['relativePublishTimeDescription'] ?? '') ?></span>
      </div>
      <p><?= nl2br($h($r['text']['text'] ?? '')) ?></p>
    </div>
  <?php endforeach; ?>

  <p><small>Datos de Google Maps. <a href="<?= $h($detalle['googleMapsUri'] ?? '#') ?>" target="_blank" rel="noopener">Ver en Google Maps</a></small></p>
</body>
</html>