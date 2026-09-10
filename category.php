<?php 
    ob_start();
    session_start();
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';

    $categoriesResponse = json_decode(API($jwt, URL_API.'categories', '', 'POST'), true);
    $categories = $categoriesResponse['data'] ?? [];

    require_once TEMPLATE.'head.php'; 

    $metaTitle = 'Categorías | '.COMPANY_NAME;
    $metaDescription = 'Explora todas las categorías de productos disponibles en '.COMPANY_NAME.'.';
    $metaUrl = rtrim(URL_BASE, '/').'/categories';
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $metaTitle,
        'description' => $metaDescription,
        'url' => $metaUrl,
        'numberOfItems' => count($categories),
        'itemListElement' => []
    ];

    foreach ($categories as $position => $category) {
        $categoryName = trim($category['Nombre'] ?? '');
        if ($categoryName === '') {
            continue;
        }

        $categoryUrl = URL_BASE.'/products/'.rawurlencode(str_replace(' ', '-', $categoryName));
        $categorySchema = [
            '@type' => 'Thing',
            'name' => $categoryName,
            'url' => $categoryUrl
        ];
        if (!empty($category['Imagen'])) {
            $categorySchema['image'] = URL_IMAGES.'/categories/thumbnails/'.$category['Imagen'];
        }

        $schema['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => $position + 1,
            'item' => $categorySchema
        ];
    }
?>
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($metaUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <script type="application/ld+json">
        <?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
    </script>
    <link rel="stylesheet" href="<?php echo URL_BASE."/";?>css/general.css">
</head>
<body>

<?php require_once TEMPLATE.'nav.php'; ?>

<div class="container py-5">
    <?php require_once TEMPLATE.'allcat.php'; ?>    
</div>

<?php require_once TEMPLATE.'social.php'; ?>

<?php require_once TEMPLATE.'cart.php'; ?>

<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>

<script src="<?php echo URL_BASE."/".TEMPLATE;?>js/idx-template.js"></script>
<script src="<?php echo URL_BASE."/";?>js/general.php"></script>
<script>


</script>
</body>
</html>