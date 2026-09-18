<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "ref"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 
    <section class="mb-5">
        <h2 class="section-title"><?= Trd(1) ?></h2>
        <div class="swiper swiperReviews">
            <div class="swiper-wrapper">
<?php
// Configuración
$api_key = GOOGLE_API_KEY;
$place_id = PLACE_ID;

// Petición a la API de Google Places
$url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . urlencode($place_id) . "&fields=name,rating,reviews&key={$api_key}&language=es";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
// Desactivar temporalmente verificación SSL si estás en entorno local (descomentar solo si es local)
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$curl_error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 1. Validar fallo a nivel de red / cURL
if ($response === false) {
    echo "<b>Error de cURL:</b> " . htmlspecialchars($curl_error);
    exit;
}

// 2. Validar código de respuesta HTTP
if ($http_code !== 200) {
    echo "<b>Error HTTP {$http_code}:</b> " . htmlspecialchars($response);
    exit;
}

// 3. Decodificar JSON
$data = json_decode($response, true);

function mostrarEstrellas($rating) {
    $estrellas = "";
    for ($i = 1; $i <= 5; $i++) {
        $estrellas .= ($i <= $rating) 
            ? "<span style='color: #f1c40f;'>★</span>" 
            : "<span style='color: #ccc;'>☆</span>";
    }
    return $estrellas;
}

// 4. Validar respuesta de la API de Google
if (isset($data['status']) && $data['status'] === 'OK') {
    if (!empty($data['result']['reviews'])) {
        foreach ($data['result']['reviews'] as $review) {
            echo "
                <div class='swiper-slide'>
                    <div class='review-card'>
                        <div class='stars'>" . mostrarEstrellas($review['rating']) . "</div>
                        <p class='fst-italic'>" . htmlspecialchars($review['text']) . "</p>
                        <h6 class='mb-0'><strong>" . htmlspecialchars($review['author_name']) . "</strong></h6>
                    </div>
                </div>
            ";
        }
    } else {
        echo "<p>El lugar fue encontrado pero no tiene reseñas disponibles.</p>";
    }
} else {
    // Imprime el error exacto que regresa Google (ej. REQUEST_DENIED, INVALID_REQUEST, OVER_QUERY_LIMIT)
    echo "<b>Error de Google API:</b> Status: " . htmlspecialchars($data['status'] ?? 'DESCONOCIDO');
    if (isset($data['error_message'])) {
        echo " - Mensaje: " . htmlspecialchars($data['error_message']);
    }
}
?>

            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>