<?php
    $api_url = URL_API . "Traducciones_web";
    $data = json_encode(array('program' => 'ref'));
    $Traducciones = json_decode(API($jwt, $api_url, $data, 'GET'), true);

    if (!function_exists('ref_html')) {
        function ref_html($value)
        {
            return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        }
    }

    if (!function_exists('ref_stars')) {
        function ref_stars($rating)
        {
            $rating = max(0, min(5, (int) round((float) $rating)));
            return str_repeat('<span class="star star-filled">&#9733;</span>', $rating)
                . str_repeat('<span class="star star-empty">&#9734;</span>', 5 - $rating);
        }
    }

    if (!function_exists('ref_get_reviews')) {
        function ref_get_reviews($apiKey, $placeId)
        {
            if (trim((string) $apiKey) === '' || trim((string) $placeId) === '') {
                return array();
            }

            $url = 'https://places.googleapis.com/v1/places/' . rawurlencode($placeId) . '?languageCode=es';
            $headers = array(
                'X-Goog-Api-Key: ' . $apiKey,
                'X-Goog-FieldMask: displayName,reviews',
            );

            $curl = curl_init($url);
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
            ));

            $response = curl_exec($curl);
            $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($response === false || $httpCode < 200 || $httpCode >= 300) {
                return array();
            }

            $result = json_decode($response, true);
            return is_array($result) && !empty($result['reviews']) ? $result['reviews'] : array();
        }
    }

    $reviews = ref_get_reviews(GOOGLE_API_KEY, PLACE_ID);
?>
    <section class="mb-5">
        <h2 class="section-title"><?= Trd(1) ?></h2>
        <div class="swiper swiperReviews">
            <div class="swiper-wrapper">
                <?php foreach ($reviews as $review):
                    $author = isset($review['authorAttribution']) ? $review['authorAttribution'] : array();
                    $authorName = isset($author['displayName']) ? $author['displayName'] : 'Anónimo';
                    $authorUrl = isset($author['uri']) ? $author['uri'] : '';
                    $photoUrl = isset($author['photoUri']) ? $author['photoUri'] : '';
                    $rating = isset($review['rating']) ? $review['rating'] : 0;
                    $reviewText = isset($review['text']['text']) ? $review['text']['text'] : '';
                    $reviewDate = isset($review['relativePublishTimeDescription']) ? $review['relativePublishTimeDescription'] : '';
                ?>
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="autor">
                                <?php if ($photoUrl !== ''): ?>
                                    <img src="<?= ref_html($photoUrl) ?>" alt="" loading="lazy">
                                <?php endif; ?>
                                <?php if ($authorUrl !== ''): ?>
                                    <a href="<?= ref_html($authorUrl) ?>" target="_blank" rel="noopener noreferrer">
                                        <?= ref_html($authorName) ?>
                                    </a>
                                <?php else: ?>
                                    <span><?= ref_html($authorName) ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <span class="estrellas"><?= ref_stars($rating) ?></span>
                                <?php if ($reviewDate !== ''): ?>
                                    <span class="fecha"><?= ref_html($reviewDate) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($reviewText !== ''): ?>
                                <p><?= nl2br(ref_html($reviewText)) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
