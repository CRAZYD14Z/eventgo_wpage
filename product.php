<?php 
    ob_start();
    session_start();
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 
?>
    <link rel="stylesheet" href="<?php echo URL_BASE."/";?>css/general.css">
</head>
<body>

<?php require_once TEMPLATE.'nav.php'; ?>
<div class="container py-5">
    <?php

    $api_url = URL_API."products";
    
    $Product = str_replace("-"," ",$_GET['Id']);
    $IdP = str_replace("-"," ",$_GET['Idp']);
    if (!isset($_GET['SD']))
        $data = json_encode(["Product" => $Product ,"IdP" => $IdP ,"SD" => date("Y-m-d"),"ED" => date("Y-m-d"),"SH" => '08:00',"EH" => '16:00']);
    else
        $data = json_encode(["Product" => $Product ,"IdP" => $IdP ,"SD" => $_GET['SD'],"ED" => $_GET['ED'],"SH" => $_GET['SH'],"EH" => $_GET['EH']]);
    $data = json_decode(API($jwt,$api_url,$data,'POST'), true);
    //print_r($data);
    if ($data['status'] === 'success') {
        ?>
        
    <style>
        /* Estilo para que la columna izquierda sea pegajosa */
        @media (min-width: 992px) {
            .sticky-column {
                position: -webkit-sticky;
                position: sticky;
                top: 20px; /* Distancia desde el borde superior de la pantalla */
                height: fit-content;
            }
        }
        
        .main-image-container {
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .main-image-container img {
            max-height: 100%;
            object-fit: contain;
        }
    </style>
    
    <style>
        /* Contenedor del carrusel */
        .carrusel-contenedor {
            display: flex;
            gap: 24px;
            overflow-x: auto;
            padding: 10px;
            max-width: 1200px;
            margin: 0 auto;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        /* Ocultar barra de scroll estándar */
        .carrusel-contenedor::-webkit-scrollbar {
            display: none;
        }
        .carrusel-contenedor {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Elemento de video */
        .item-video {
            flex: 0 0 340px;
            transition: opacity 0.3s ease;
            opacity: 0.75;
        }

        .item-video:hover {
            opacity: 1;
        }

        /* Contenedor 16:9 */
        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%; 
            height: 0;
            overflow: hidden;
            border-radius: 6px;
            background-color: #f5f5f7;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .titulo-video {
            margin-top: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #222222;
            line-height: 1.4;
        }

        /* Contenedor de los puntos (Dots) */
        .indicadores-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
        }

        /* Estilo base de cada punto */
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #e0e0e0;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        /* Estilo del punto activo */
        .dot.activo {
            background-color: #111111;
            transform: scale(1.2); /* Se hace ligeramente más grande */
        }
    </style>    

<?php 
    $api_url = URL_API."Traducciones_web";
    $datat = json_encode(['program' => "producto"]);
    $Traducciones = json_decode(API($jwt,$api_url,$datat,'GET'), true);
    foreach ($data['data'] as $producto) {

?>
    <div class="row">
        
        <div class="col-lg-6 mb-4">
            <div class="sticky-column">
                <div class="main-image-container mb-3 border rounded bg-white shadow-sm">
                    <?php 
                        foreach ($data['Image'] as $Image) {
                            $URLImagep = URL_IMAGES.'/products_images/thumbnails/'.$Image['Image'];
                            echo "<img id='mainProductImage' src='$URLImagep' class='img-fluid' alt='Producto'>";
                        }
                    ?>
                </div>

                <div id="productThumbnails" class="p-2 border rounded bg-light shadow-sm">
                    <div class="d-flex justify-content-center overflow-auto">
                        <?php 
                            foreach ($data['Images'] as $Image) {
                                $URLImage = URL_IMAGES.'/products_images/thumbnails/'.$Image['Image'];
                                echo "<img src='$URLImage' class='img-thumbnail me-2' style='width: 70px; cursor: pointer;' onclick='changeImage(this.src)'>";
                            }
                        ?>
                    </div>
                    <p class="text-center small text-muted mt-2 mb-0"><?= Trd(3) ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <h1 class="fw-bold mb-3"><?php echo $producto['Name']?></h1>
            <div class="h3 text-primary mb-4">$<?php echo  number_format($data['Resultadosp'][0]['Price'], 2, '.', ',');  ?></div>

                <div class="d-flex align-items-center gap-2 mb-4" style="max-width: 400px;">
                    
                    <div class="input-group" style="width: 140px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        
                        <input type="text" id="cart-qty" 
                            class="form-control text-center fw-bold border-secondary" 
                            value="1" readonly 
                            autocomplete="off"
                            style="background-color: #fff;">
                        
                        <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <button class="btn btn-primary flex-grow-1" onclick="add_cart()">
                        <i class="fas fa-shopping-cart me-2"></i><?= Trd(1) ?>
                    </button>
                </div>

                <div class="mb-4">
                    <span class="badge rounded-pill bg-light text-dark border">
                        <i class="fas fa-boxes me-1 text-primary"></i> 
                        <?= Trd(2) ?>: <span id="stock-val">0</span>
                    </span>
                </div>            

            <hr>
                <div class="product-details">                
                    <?php echo $producto['Description']?>
                    <h5 class="mt-4 fw-bold"><?= Trd(4) ?></h5>
                    <p><?php echo $producto['ActualSize']?></p>
                    <h5 class="mt-4 fw-bold"><?= Trd(5) ?></h5>
                    <p><?php echo $producto['SpaceRequired']?></p>
                    <h5 class="mt-4 fw-bold"><?= Trd(6) ?></h5>
                    <p><?php echo $producto['Weight']?></p>
                </div>    
        </div>

    </div>

    <?php if ($data['Videos']):?>
    <div class="seccion-siguiente2">
        <h2 class="font-title" >VIDEOS</h2>
    </div>        

    <div class="col-lg-12 border rounded bg-white shadow-sm" >        
        <br>
        <div class="carrusel-contenedor" id="carrusel">

            <?php 
                foreach ($data['Videos'] as $Video) {
                    echo '
                    <div class="item-video">
                        <div class="video-wrapper">
                            <iframe src="'.$Video['Video'].'" title="'.$Video['Title'].'" allowfullscreen></iframe>
                        </div>
                        <div class="titulo-video">'.$Video['Title'].'</div>
                    </div>
                    ';
                }
            ?>    

        </div>

        <div class="indicadores-dots" id="contenedor-dots">
        </div>  
        <br>
    </div>    
    <?php endif;?>    


        <?php
        }
    } else {
        echo "<h1>Error en la API</h1>";
        echo "Mensaje: " . ($data['message'] ?? 'Error desconocido');
    }
    $dataRsp = $data;
    ?>

    <hr class="my-5">
    <?php 
    $Title=Trd(7);
    $SubTitle="Accesorios";
    $SSubTitle="Accesorios";
    require_once TEMPLATE.'accesories.php'; ?>
    <hr class="my-5">
    <?php 
    
    $Title=Trd(8);
    $SubTitle="Accesorios";
    $SSubTitle="Accesorios";    
    require_once TEMPLATE.'interested.php'; ?>

</div>

<?php require_once TEMPLATE.'social.php'; ?>
<?php require_once TEMPLATE.'cart.php'; ?>
<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>

<script src="<?php echo URL_BASE."/".TEMPLATE;?>js/idx-template.js"></script>
<script src="<?php echo URL_BASE."/";?>js/general.php"></script>
<?php
    $data = $dataRsp;
?>
<script>

    var MAX_STOCK = <?php echo $data['Resultadosp'][0]['Quantity']?>; 
    const actualID = <?php echo $producto['Id']; ?>;
    const qtyInput = document.getElementById('cart-qty');

    function changeImage(src) {
        document.getElementById('mainProductImage').src = src;
    }

    function add_cart(){
        const miProducto = {
            id: <?php echo $producto['Id']; ?>, // ID único de tu base de datos (MySQL)
            nombre: "<?php echo $producto['Name']; ?>",
            precio: <?php echo $data['Resultadosp'][0]['Price']?>,
            imagen: "<?php echo $URLImagep;?>",
            url: $(location).prop('href'),
            existencia:$('#stock-val').html(),
            adicionales: []
        };

        const qtyInput = document.getElementById('cart-qty');

        if (qtyInput.value <= MAX_STOCK){
            MAX_STOCK = MAX_STOCK - qtyInput.value ;
            $('#stock-val').html(MAX_STOCK);        
            agregarAlCarrito(miProducto,parseInt(qtyInput.value));
        }
    }

    function adjustQty(amount) {
        let current = parseInt(qtyInput.value);
        let newValue = current + amount;

        // Validación estricta: Mínimo 1, Máximo disponible
        if (newValue >= 1 && newValue <= MAX_STOCK) {
            qtyInput.value = newValue;
            $('#stock-val').html(MAX_STOCK - newValue);
        }
        if (newValue == 1)
            $('#stock-val').html(MAX_STOCK);
    }

    $(document).ready(function() {
        let carrito = obtenerCarrito();
        const item = carrito.find(p => p.id == actualID );    
        if (item) {
            MAX_STOCK = MAX_STOCK - item.cantidad ;
            
        }
        $('#stock-val').html(MAX_STOCK);
    });    
</script>

<script>
    const carrusel = document.getElementById('carrusel');
    const contenedorDots = document.getElementById('contenedor-dots');
    const videos = document.querySelectorAll('.item-video');

    // 1. Generar los puntos dinámicamente según la cantidad de videos
    videos.forEach((_, indice) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (indice === 0) dot.classList.add('activo'); // El primero empieza activo
        
        // Hacer que los puntos sean clickeables para mover el carrusel
        dot.addEventListener('click', () => {
            const anchoVideo = videos[0].offsetWidth + 24; // Ancho del video + el espacio (gap)
            carrusel.scrollLeft = anchoVideo * indice;
        });

        contenedorDots.appendChild(dot);
    });

    const dots = document.querySelectorAll('.dot');

    // 2. Función para actualizar qué punto se ilumina al hacer scroll
    function actualizarDots() {
        const anchoVideo = videos[0].offsetWidth + 24;
        // Calculamos cuál es el video más visible en pantalla actualmente
        const indiceActivo = Math.round(carrusel.scrollLeft / anchoVideo);

        dots.forEach((dot, indice) => {
            if (indice === indiceActivo) {
                dot.classList.add('activo');
            } else {
                dot.classList.remove('activo');
            }
        });
    }

    // Escuchar el evento de scroll (sirve para rueda de ratón y scroll táctil en móvil)
    carrusel.addEventListener('scroll', actualizarDots);

    // 3. Mantener el soporte para transformar scroll vertical del ratón en horizontal
    carrusel.addEventListener('wheel', (e) => {
        if (e.deltaY !== 0) {
            e.preventDefault();
            carrusel.scrollLeft += e.deltaY * 1.2;
        }
    });
</script>

</body>
</html>
