<?php 
    ob_start();
    session_start();
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 
?>
    <script type="text/javascript" src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
    <script type='text/javascript' src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>        
    <link rel="stylesheet" href="css/general.css">
    <style>

        .card-payment { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .form-control { border-radius: 8px; padding: 12px; border: 1px solid #dee2e6; }
        .form-control:focus { box-shadow: none; border-color: #000; }
        .btn-pay { background: #000; color: #fff; border: none; padding: 14px; border-radius: 8px; font-weight: 600; transition: 0.3s; }
        .btn-pay:hover { background: #333; color: #fff; }
        .btn-pay:disabled { background: #ccc; }
        .input-group-text { background: transparent; border-radius: 8px; }
        .anticipo-card { cursor: pointer; border: 2px solid #eee; border-radius: 10px; transition: 0.2s; }
        .anticipo-card:hover { border-color: #000; }
        .selected-anticipo { border-color: #000 !important; background-color: #f8f9fa; }
        .text-detail { font-size: 0.85rem; color: #6c757d; }


    </style>
</head>
<body>

<?php 
    require_once TEMPLATE.'nav.php'; 
    
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "spayment"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);    
?>




<?php
        if (!isset($_GET['Id'])){
            echo Trd(1);
            die();
        }

        $token = $_GET['Id']; // El UUID de la URL
        $TId = $_GET['TId']; // El UUID de la URL
        $ahora = date("Y-m-d H:i:s");


        $api_url = URL_API."quotes";
        $data = json_encode(['token' => $token]);
        $cotizacion = json_decode(API($jwt,$api_url,$data,'GET'), true);

        if ($cotizacion) {
            // Verificar si la fecha actual es mayor a la de expiración
            if ($ahora > $cotizacion['ExpDate']) {
                echo Trd(2)." " . $cotizacion['ExpDate']." $ahora";
                die();
            }
        } else {
            echo Trd(1);
            die();
        }


        $api_url = URL_API."quote_account";
        //$data = json_encode(['token' => $token]);
        $data ='';
        $account = json_decode(API($jwt,$api_url,$data,'GET'), true);


        //$api_url = URL_API."document_center";
        //$data = json_encode(['Tipo' => 'email','IdTemplate' => 8,'Idioma' => $lang]);
        //$Template = json_decode(API($jwt,$api_url,$data,'GET'), true);        


        $api_url = URL_API."quote_data";
        $data = json_encode(['lead' => $cotizacion['IdQuote']]);        
        $respuesta = json_decode(API($jwt,$api_url,$data,'GET'), true);

        $lead = $respuesta['lead'];
        $lead_details =  $respuesta['lead_details'];
        $customer = $respuesta['customer'];        
        $organization =  $respuesta['organization'];
        $venue =  $respuesta['venue'];
        $discounts =  $respuesta['discounts'];
        $gifcrds =  $respuesta['gifcrds'];    

        // Supongamos que estas son tus variables con los datos de la DB
        //$pdfContenido = $cotizacion['Contrato']; // El binario del PDF
        $pdfNombre = $cotizacion['UUID'].".PDF";   // El nombre original (ej: "contrato_45.pdf")

        // Convertimos a Base64
        $base64 = $cotizacion['Contrato'];

        // Creamos el Data URI
        $pdfDataUri = "data:application/pdf;base64," . $base64;   
/*
        function generarHtmlCotizacion($html, $datos) {
            $busqueda = array_map(function($key) {
                return '*' . $key . '*';
            }, array_keys($datos));
            $sustitucion = array_values($datos);
            return str_replace($busqueda, $sustitucion, $html);
        }
*/

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-payment p-4  text-center">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
            </div>
            
            <h1 class="fw-bold text-dark"><?= Trd(3) ?></h1>
            <p class="lead text-muted mb-5"><?= Trd(4) ?></p>

            <div class="card border-0 shadow-sm bg-light mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted"><?= Trd(5) ?></span>
                        <span class="fw-bold text-success">+$<?php echo number_format($lead['SubTotal'] + $lead['TaxAmount'] + $lead['Tip'] - $lead['Balance'], 2, '.', ',') ;?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted"><?= Trd(6) ?></span>
                        <span class="fw-medium">$<?php echo number_format($lead['SubTotal'] + $lead['TaxAmount'] + $lead['Tip'] , 2, '.', ',') ;?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark"><?= Trd(7) ?></span>
                        <h4 class="fw-bold mb-0 text-primary">$<?php echo number_format($lead['Balance'], 2, '.', ',') ;?></h4>
                    </div>
                    <?php 
                    if ($gifcrds){
                        $SaldoGC = 0;
                        foreach ($gifcrds as $discount) {
                            $SaldoGC = $discount['gifcardAmount'];
                        }
                        //$SaldoGC = $SaldoGC - $lead['SubTotal'] + $lead['TaxAmount'] + $lead['Tip'];
                    ?>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark"><?= Trd(8) ?></span>
                        <h4 class="fw-bold mb-0 text-primary">$<?php echo number_format($SaldoGC, 2, '.', ',') ;?></h4>
                    </div>                    

                    <?php }?>
                    <small class="d-block text-center mt-3 text-muted">
                        * <?= Trd(9) ?>
                    </small>
                </div>
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">

                <a href="<?php echo $pdfDataUri; ?>" 
                download="<?php echo $pdfNombre; ?>" 
                class="btn btn-primary btn-lg px-4 gap-3">
                <?= Trd(10) ?>
                </a>            

                <a href="<?php echo URL_BASE;?>" class="btn btn-outline-secondary btn-lg px-4"><?= Trd(11) ?></a>
            </div>
            
            <p class="mt-5 text-muted small"><?= Trd(12) ?>: # <?php echo $TId;?></p>
        </div>            
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<?php require_once TEMPLATE.'social.php'; ?>
<?php require_once TEMPLATE.'cart.php'; ?>
<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>
<script src="<?php echo TEMPLATE;?>js/idx-template.js"></script>
<script src="js/general.php"></script>
<script>
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href);
        alert("<?= Trd(13) ?>");
    };
</script>
</body>
</html>