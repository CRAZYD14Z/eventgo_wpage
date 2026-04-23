<?php 
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 
?>
    <link rel="stylesheet" href="css/general.css">
    <style>

</style>
</head>
<body>

<?php require_once TEMPLATE.'nav.php'; ?>

<div class="container py-5">

<?php
    $rutaArchivo = '023f52b8-7ebe-44fb-8448-8ac288757304.pdf';
    $contenidoBase64 = base64_encode(file_get_contents($rutaArchivo));
    $data = json_encode([
        'correo' => 'jdiaz_huerta@hotmail.com',
        'archivo_base64' => $contenidoBase64,
        'nombre_archivo' => $rutaArchivo,
        'Subject'=> 'Correo de prueba',
        'Body'=> 'Correo de prueba'
    ]);

    $api_url = URL_API."sendmail";
    $data = json_decode(API($jwt,$api_url,$data,'POST'), true);    
    print_r($data);
?>


</div>

<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>
<script src="<?php echo TEMPLATE;?>js/idx-template.js"></script>
<script src="js/general.js"></script>
<script>

</script>
</body>
</html>



