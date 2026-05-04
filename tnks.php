<?php 
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 
?>
    <link rel="stylesheet" href="<?php echo URL_BASE."/";?>css/general.css">
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">       

</head>

<body>

<?php require_once TEMPLATE.'nav.php'; ?>
<?php 
$idLead         = $_GET['IdLead'] ?? null;
$tokenRecibido  = $_GET['token'] ?? null;
$idCheckout     = $_GET['id'] ?? null;

$api_url = URL_API."tnks";
$data = json_encode(["idLead" => $idLead,"token" => $tokenRecibido,"idCheckout"=>$idCheckout]);
$data = json_decode(API($jwt,$api_url,$data,'GET'), true);
//echo $api_url."*".$data;
//die();
if ($data) {
    $pagoRegistrado = $data['status'];
    $mensaje = $data['mensaje'];
    $transactionId  = $data['transactionId'];
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-lg border-0 p-5">
                <?php if ($pagoRegistrado): ?>
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="fw-bold">¡Gracias por tu pago!</h1>
                    <p class="text-muted fs-5"><?php echo $mensaje; ?></p>
                    <hr>
                    <p>ID de Transacción: <strong><?php echo $transactionId ?? 'N/A'; ?></strong></p>
                    <a href="index.php" class="btn btn-primary btn-lg mt-3">Volver al Inicio</a>
                <?php else: ?>
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="fw-bold">Atención</h1>
                    <p class="text-muted"><?php echo $mensaje; ?></p>
                    <a href="index.php" class="btn btn-outline-secondary mt-3">Regresar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once TEMPLATE.'social.php'; ?>

<?php require_once TEMPLATE.'cart.php'; ?>

<?php require_once TEMPLATE.'scripts.php'; ?>

<script src="<?php echo TEMPLATE;?>js/idx-template.js"></script>
<?php require_once 'scripts.php'; ?>
<script src="js/general.js"></script>


</body>
</html>