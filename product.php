<?php 
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
    if (!isset($_GET['SD']))
        $data = json_encode(["Product" => $Product ,"SD" => date("Y-m-d"),"ED" => date("Y-m-d"),"SH" => '08:00',"EH" => '16:00']);
    else
        $data = json_encode(["Product" => $Product ,"SD" => $_GET['SD'],"ED" => $_GET['ED'],"SH" => $_GET['SH'],"EH" => $_GET['EH']]);
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
<?php 

    foreach ($data['data'] as $producto) {

?>
    <div class="row">
        
        <div class="col-lg-6 mb-4">
            <div class="sticky-column">
                <div class="main-image-container mb-3 border rounded bg-white shadow-sm">
                    <?php 
                        foreach ($data['Image'] as $Image) {
                            $URLImagep = URL_IMAGES.$Image['Image'];
                            echo "<img id='mainProductImage' src='$URLImagep' class='img-fluid' alt='Producto'>";
                        }
                    ?>
                </div>

                <div id="productThumbnails" class="p-2 border rounded bg-light shadow-sm">
                    <div class="d-flex justify-content-center overflow-auto">
                        <?php 
                            foreach ($data['Images'] as $Image) {
                                $URLImage = URL_IMAGES.$Image['Image'];
                                echo "<img src='$URLImage' class='img-thumbnail me-2' style='width: 70px; cursor: pointer;' onclick='changeImage(this.src)'>";
                            }
                        ?>
                    </div>
                    <p class="text-center small text-muted mt-2 mb-0">Galería de imágenes</p>
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
                        <i class="fas fa-shopping-cart me-2"></i>Agregar
                    </button>
                </div>

                <div class="mb-4">
                    <span class="badge rounded-pill bg-light text-dark border">
                        <i class="fas fa-boxes me-1 text-primary"></i> 
                        Disponibles: <span id="stock-val">0</span>
                    </span>
                </div>            

            <hr>
                <div class="product-details">                
                    <?php echo $producto['Description']?>
                    <h5 class="mt-4 fw-bold">Dimensiones</h5>
                    <p><?php echo $producto['ActualSize']?></p>
                    <h5 class="mt-4 fw-bold">Espacio Requerido</h5>
                    <p><?php echo $producto['SpaceRequired']?></p>
                    <h5 class="mt-4 fw-bold">Peso</h5>
                    <p><?php echo $producto['Weight']?></p>
                </div>    
        </div>

    </div>

        <?php
        }
    } else {
        echo "<h1>Error en la API</h1>";
        echo "Mensaje: " . ($data['message'] ?? 'Error desconocido');
    }
    ?>

    <hr class="my-5">
    <?php 
    $Title="Accesorios";
    $SubTitle="Accesorios";
    $SSubTitle="Accesorios";
        require_once TEMPLATE.'accesories.php'; ?>
    <hr class="my-5">
    <?php 
    
    $Title="Podrias estar interesado en:";
    $SubTitle="Accesorios";
    $SSubTitle="Accesorios";    
    require_once TEMPLATE.'interested.php'; ?>
<?php
    //print_r($data['Resultadosp'])
?>
</div>

<?php require_once TEMPLATE.'social.php'; ?>

<?php require_once TEMPLATE.'cart.php'; ?>

<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>

<script src="<?php echo URL_BASE."/".TEMPLATE;?>js/idx-template.js"></script>
<script src="<?php echo URL_BASE."/";?>js/general.js"></script>
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
</body>
</html>
