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

    <hr class="my-5">
    <?php 
    $Category = str_replace("-"," ",$_GET['Id']);    
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
<script src="<?php echo URL_BASE."/";?>js/general.js"></script>
<script>

    $(document).ready(function() {
        
        const contenedor = document.getElementById('contenedor-productos')
        $.ajax({
            url: url_api +'products_categories',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({Category: '<?php echo $Category?>',FI:$('#FI').val(),FF:$('#FF').val(),HI:$('#hInicio').val(),HF:$('#hFin').val()}),
            headers: {
                'Authorization': 'Bearer ' + token
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
            const url = '<?php echo URL_BASE; ?>/product/' + product.ProductName.replace(/ /g, '-')+'?SD='+$('#FI').val()+'&ED='+$('#FF').val()+'&SH='+$('#hInicio').val()+'&EH='+$('#hFin').val();
            const imagenUrl = '<?php echo URL_IMAGES; ?>' + product.Image;
            
            const productoHTML = `
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="${url}" class="cat-card">
                        <img height="150px" src="${imagenUrl}" alt="${product.ProductName}">
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