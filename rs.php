<?php
/**
 * Recuperar reseñas de Google Maps a partir de una URL SIN Place ID.
 *
 * Flujo:
 *   1. Se extraen de la URL: nombre, coordenadas y CID (identificador numérico).
 *   2. Text Search (Places API New) -> se obtiene el Place ID real (ChIJ...).
 *   3. Place Details (Places API New) -> se piden las reseñas.
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

const MAPS_URL = 'https://www.google.com/maps/place/Robinsons+Rentals/@34.485354,-117.3560169,59599m/data=!3m1!1e3!4m12!1m5!3m4!1s0x4eaf0239cc97e119:0x777b4711a8f74a1e!2sRobinsons+Rentals!11m1!2e1!3m5!1s0x4eaf0239cc97e119:0x777b4711a8f74a1e!8m2!3d34.4820709!4d-117.3703549!16s%2Fg%2F11ny0gdl0d?entry=ttu&g_ep=EgoyMDI2MDkxNS4wIKXMDSoASAFQAw%3D%3D';

/* ------------------------------------------------------------------ */
/* 1. Extraer datos de la URL                                          */
/* ------------------------------------------------------------------ */

function parsearUrlMaps(string $url): array
{
    $datos = ['nombre' => null, 'lat' => null, 'lng' => null, 'cid' => null];

    // Nombre: /place/Robinsons+Rentals/
    if (preg_match('#/place/([^/@]+)#', $url, $m)) {
        $datos['nombre'] = urldecode(str_replace('+', ' ', $m[1]));
    }

    // Coordenadas exactas del negocio: !3d<lat>!4d<lng>
    if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $m)) {
        $datos['lat'] = (float) $m[1];
        $datos['lng'] = (float) $m[2];
    }

    // Feature ID: !1s0x<hex>:0x<hex>  -> la segunda parte en decimal es el CID
    if (preg_match('/!1s0x[0-9a-f]+:0x([0-9a-f]+)/i', $url, $m)) {
        $datos['cid'] = hexToDec($m[1]);
    }

    return $datos;
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
    return (string) hexdec($hex); // válido mientras quepa en 64 bits con signo
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
        throw new RuntimeException("Error de red: $error");
    }
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $json = json_decode($respuesta, true);
    if ($http >= 400) {
        $msg = $json['error']['message'] ?? $respuesta;
        throw new RuntimeException("La API respondió HTTP $http: $msg");
    }

    return $json ?? [];
}

/* ------------------------------------------------------------------ */
/* 3. Buscar el Place ID (Text Search)                                 */
/* ------------------------------------------------------------------ */

function buscarPlaceId(array $datos): array
{
    $cuerpo = [
        'textQuery'    => $datos['nombre'],
        'languageCode' => 'es',
        'maxResultCount' => 5,
    ];

    // Sesgar la búsqueda a las coordenadas del negocio (radio de 500 m)
    if ($datos['lat'] !== null && $datos['lng'] !== null) {
        $cuerpo['locationBias'] = [
            'circle' => [
                'center' => ['latitude' => $datos['lat'], 'longitude' => $datos['lng']],
                'radius' => 500.0,
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
        throw new RuntimeException('No se encontró ningún lugar con ese nombre.');
    }

    // Confirmar que es el mismo negocio comparando el CID de la URL
    // (googleMapsUri devuelve algo como https://maps.google.com/?cid=8609553253823629854)
    if ($datos['cid']) {
        foreach ($lugares as $lugar) {
            if (isset($lugar['googleMapsUri']) && strpos($lugar['googleMapsUri'], 'cid=' . $datos['cid']) !== false) {
                return $lugar;
            }
        }
    }

    // Si no hay coincidencia exacta de CID, se usa el primer resultado
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

try {
    $datos  = parsearUrlMaps(MAPS_URL);
    $lugar  = buscarPlaceId($datos);
    $placeId = $lugar['id'];
    $detalle = obtenerResenas($placeId);
} catch (Throwable $e) {
    if (!$esCli) {
        header('Content-Type: text/plain; charset=utf-8');
    }
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

$nombre   = $detalle['displayName']['text'] ?? $datos['nombre'];
$resenas  = $detalle['reviews'] ?? [];

if ($esCli) {
    echo "Negocio:  $nombre\n";
    echo "Place ID: $placeId\n";
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
    <br><small>Place ID: <?= $h($placeId) ?></small>
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