<?php 
    ob_start();
    session_start();
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';

    $Category = str_replace("-"," ",$_GET['Id'] ?? '');
    $categoryPayload = json_encode([
        'Category' => $Category,
        'FI' => date('Y-m-d'),
        'FF' => date('Y-m-d'),
        'HI' => '08:00',
        'HF' => '16:00'
    ]);
    $categoryResponse = json_decode(API($jwt, URL_API.'products_categories', $categoryPayload, 'POST'), true);
    $categoryProducts = $categoryResponse['data'] ?? [];

    require_once TEMPLATE.'head.php'; 

    $metaTitle = 'Productos de '.$Category;
    $metaDescription = 'Consulta los productos disponibles de la categoría '.$Category.'.';
    $metaUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
        .'://'.($_SERVER['HTTP_HOST'] ?? '').($_SERVER['REQUEST_URI'] ?? '');
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $metaTitle,
        'description' => $metaDescription,
        'url' => $metaUrl,
        'numberOfItems' => count($categoryProducts),
        'itemListElement' => []
    ];

    foreach ($categoryProducts as $position => $product) {
        $productName = trim($product['ProductName'] ?? '');
        $productSlug = str_replace(' ', '-', $productName);
        $productUrl = URL_BASE.'/product/'.rawurlencode($productSlug)
            .'?Idp='.rawurlencode((string) ($product['Producto'] ?? ''));
        $productImage = !empty($product['Image'])
            ? URL_IMAGES.'/products_images/thumbnails/'.$product['Image']
            : null;
        $productSchema = [
            '@type' => 'Product',
            'name' => $productName,
            'url' => $productUrl
        ];

        if ($productImage !== null) {
            $productSchema['image'] = $productImage;
        }
        if (isset($product['Price'])) {
            $productSchema['offers'] = [
                '@type' => 'Offer',
                'priceCurrency' => $account['Currency'] ?? 'USD',
                'price' => (float) $product['Price'],
                'url' => $productUrl,
                'availability' => 'https://schema.org/InStock'
            ];
        }

        $schema['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => $position + 1,
            'item' => $productSchema
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

    <hr class="my-5">
    <?php 
    $Title="Categoria";
    $SubTitle="Accesorios";
    $SSubTitle="Accesorios";
    require_once TEMPLATE.'products.php'; 
    ?>
</div>

<?php require_once TEMPLATE.'social.php'; ?>

<?php require_once TEMPLATE.'cart.php'; ?>

<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>

<script src="<?php echo URL_BASE."/".TEMPLATE;?>js/idx-template.js"></script>
<script src="<?php echo URL_BASE."/";?>js/general.php"></script>
<script>

    $(document).ready(function() {
        
        const contenedor = document.getElementById('contenedor-productos')
        $.ajax({
            url: url_api +'products_categories',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({Category: '<?php echo $Category?>',FI:$('#FI').val(),FF:$('#FF').val(),HI:$('#hInicio').val(),HF:$('#hFin').val()}),
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-ID-CLIENT': '<?= ID_CLIENT ?>',
                'LNG':'<?= $_SESSION['Idioma'] ?>'
            },        
            beforeSend: function() {
                contenedor.innerHTML = '<div class="text-center">Cargando productos...</div>';
            },
            success: function(response) {
                if (response.status === 'success') {
                    
                    mostrarProductos(response.data);
                } else {
                    contenedor.innerHTML = '<div class="text-center">No se encontraron productos</div>';
                }
            },
            error: function() {
                contenedor.innerHTML = '<div class="text-center text-danger">Error al cargar productos</div>';
            }
        });    

    });

    function mostrarProductos(productos) {
        const contenedor = document.getElementById('contenedor-productos');
        contenedor.innerHTML = '';
        
        if(productos.length === 0) {
            contenedor.innerHTML = '<div class="text-center">No hay productos disponibles</div>';
            return;
        }
        
        productos.forEach(product => {
            const url = '<?php echo URL_BASE; ?>/product/' + product.ProductName.replace(/ /g, '-')+'?Idp='+product.Producto+'&SD='+$('#FI').val()+'&ED='+$('#FF').val()+'&SH='+$('#hInicio').val()+'&EH='+$('#hFin').val();
            const imagenUrl = '<?php echo URL_IMAGES; ?>/products_images/thumbnails/' + product.Image;
            
            const productoHTML = `
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="${url}" class="cat-card">
                        <img width="100px" src="${imagenUrl}" alt="${product.ProductName}">
                        <b>${product.ProductName}</b> <br> $${product.Price.toFixed(2)}
                    </a>
                </div>
            `;
            
            contenedor.innerHTML += productoHTML;
        });
    }
 
</script>
</body>
</html>