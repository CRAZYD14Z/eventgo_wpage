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
$api_key = GOOGLE_API_KEY;
$place_id = PLACE_ID; // Place ID guardado

function obtenerResenasPorPlaceId($place_id, $api_key) {
    $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . urlencode($place_id) . "&fields=name,rating,reviews&key={$api_key}&language=es";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Intentar la consulta inicial
$data = obtenerResenasPorPlaceId($place_id, $api_key);

// Si el Place ID está caducado (NOT_FOUND) o no existe
if (isset($data['status']) && $data['status'] === 'NOT_FOUND') {
    
    // ID corregido/actualizado para Robinsons Rentals
    $place_id_fallback = "ChIJGfG3zDkCr04RHkr3qBFHe3c";
    $data = obtenerResenasPorPlaceId($place_id_fallback, $api_key);
}

function mostrarEstrellas($rating) {
    $estrellas = "";
    for ($i = 1; $i <= 5; $i++) {
        $estrellas .= ($i <= $rating) 
            ? "<span style='color: #f1c40f;'>★</span>" 
            : "<span style='color: #ccc;'>☆</span>";
    }
    return $estrellas;
}

// Renders en Swiper
if (isset($data['status']) && $data['status'] === 'OK' && !empty($data['result']['reviews'])) {
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


$api_key = GOOGLE_API_KEY;

// Consulta de texto buscando el negocio exacto
$query = urlencode("Robinson's Rentals Hesperia CA");
$url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query={$query}&key={$api_key}&language=es";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['status']) && $data['status'] === 'OK' && !empty($data['results'][0]['place_id'])) {
    // Obtenemos el Place ID dinámico real que Google asignó en el momento
    $real_place_id = $data['results'][0]['place_id'];

    // Ahora pedimos los detalles con ese ID recién obtenido
    $details_url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$real_place_id}&fields=name,rating,reviews&key={$api_key}&language=es";

    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $details_url);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    $details_response = curl_exec($ch2);
    curl_close($ch2);

    $details_data = json_decode($details_response, true);
    
    print_r($details_data);
} else {
    echo "No se encontró el lugar por nombre.";
}

}
?>

            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>