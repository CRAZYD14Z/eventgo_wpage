<?php
    ob_start();
    session_start();
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';

    header('Content-Type: application/xml; charset=utf-8');

    function sitemapUrl(string $url, string $lastmod): string
    {
        return "    <url>\n"
            .'        <loc>'.htmlspecialchars($url, ENT_XML1, 'UTF-8')."</loc>\n"
            .'        <lastmod>'.$lastmod."</lastmod>\n"
            ."    </url>\n";
    }

    function sitemapSlug(string $value): string
    {
        return str_replace(' ', '-', trim($value));
    }

    $today = date('Y-m-d');
    $urls = [];

    $addUrl = static function (string $url) use (&$urls, $today): void {
        $urls[$url] = sitemapUrl($url, $today);
    };

    $addUrl(rtrim(URL_BASE, '/').'/');
    $addUrl(rtrim(URL_BASE, '/').'/categories');

    $categoriesResponse = json_decode(API($jwt, URL_API.'categories', '', 'POST'), true);
    $categories = $categoriesResponse['data'] ?? [];

    foreach ($categories as $category) {
        $categoryName = trim($category['Nombre'] ?? '');
        if ($categoryName === '') {
            continue;
        }

        $categorySlug = sitemapSlug($categoryName);
        $addUrl(URL_BASE.'/products/'.rawurlencode($categorySlug));

        $productsPayload = json_encode([
            'Category' => $categoryName,
            'FI' => $today,
            'FF' => $today,
            'HI' => '08:00',
            'HF' => '16:00'
        ]);
        $productsResponse = json_decode(
            API($jwt, URL_API.'products_categories', $productsPayload, 'POST'),
            true
        );

        foreach ($productsResponse['data'] ?? [] as $product) {
            $productName = trim($product['ProductName'] ?? '');
            $productId = $product['Producto'] ?? null;
            if ($productName === '' || $productId === null || $productId === '') {
                continue;
            }

            $productUrl = URL_BASE.'/product/'.rawurlencode(sitemapSlug($productName))
                .'?Idp='.rawurlencode((string) $productId);
            $addUrl($productUrl);
        }
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo "\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    echo implode('', $urls);
    echo "</urlset>\n";
