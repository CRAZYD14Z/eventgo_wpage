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

            $language = isset($_SESSION['Idioma']) ? strtolower(trim((string) $_SESSION['Idioma'])) : 'es';
            $language = in_array($language, array('es', 'en'), true) ? $language : 'es';
            $url = 'https://places.googleapis.com/v1/places/' . rawurlencode($placeId)
                . '?languageCode=' . rawurlencode($language);
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
        <div class="swiper swiperReviews reviews-carousel">
            <div class="swiper-wrapper reviews-track">
                <?php foreach ($reviews as $review):
                    $author = isset($review['authorAttribution']) ? $review['authorAttribution'] : array();
                    $authorName = isset($author['displayName']) ? $author['displayName'] : 'Anónimo';
                    $authorUrl = isset($author['uri']) ? $author['uri'] : '';
                    $photoUrl = isset($author['photoUri']) ? $author['photoUri'] : '';
                    $rating = isset($review['rating']) ? $review['rating'] : 0;
                    $reviewText = isset($review['text']['text']) ? $review['text']['text'] : '';
                    $reviewDate = isset($review['relativePublishTimeDescription']) ? $review['relativePublishTimeDescription'] : '';
                ?>
                    <div class="swiper-slide review-slide">
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

    <style>
        .reviews-carousel {
            padding: 8px 46px 34px;
            overflow: hidden;
        }

        .reviews-track {
            align-items: stretch;
        }

        .review-slide {
            height: auto;
            display: flex;
        }

        .reviews-carousel .review-card {
            width: 100%;
            min-height: 230px;
            margin: 8px 0;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid #e8edf3;
            border-left: 4px solid var(--primary-color, #0d6efd);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(30, 55, 90, 0.09);
        }

        .reviews-carousel .autor {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 42px;
            margin-bottom: 14px;
        }

        .reviews-carousel .autor img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #edf2f7;
        }

        .reviews-carousel .autor a,
        .reviews-carousel .autor span {
            color: #263544;
            font-weight: 700;
            text-decoration: none;
        }

        .reviews-carousel .estrellas {
            display: inline-flex;
            gap: 2px;
            margin-bottom: 10px;
            font-size: 1.05rem;
            letter-spacing: 1px;
        }

        .reviews-carousel .star-filled {
            color: #f4b942;
        }

        .reviews-carousel .star-empty {
            color: #cbd5df;
        }

        .reviews-carousel .fecha {
            margin-left: 8px;
            color: #7b8794;
            font-size: .78rem;
        }

        .reviews-carousel .review-card p {
            margin: 0;
            color: #536273;
            line-height: 1.65;
        }

        .reviews-carousel .swiper-button-next,
        .reviews-carousel .swiper-button-prev {
            width: 34px;
            height: 34px;
            margin-top: -17px;
            border-radius: 50%;
            color: var(--primary-color, #0d6efd);
            background: #fff;
            box-shadow: 0 4px 14px rgba(30, 55, 90, 0.14);
        }

        .reviews-carousel .swiper-button-next:after,
        .reviews-carousel .swiper-button-prev:after {
            font-size: 14px;
            font-weight: 700;
        }

        @media (max-width: 575px) {
            .reviews-carousel {
                padding-right: 30px;
                padding-left: 30px;
            }

            .reviews-carousel .review-card {
                min-height: 210px;
                padding: 20px;
            }
        }
    </style>
