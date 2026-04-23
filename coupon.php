<?php 
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 
?>
    <link rel="stylesheet" href="<?php echo URL_BASE."/";?>css/general.css">
    <link rel="stylesheet" href="<?php echo URL_BASE."/".TEMPLATE;?>css/couponpage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">

    <style>

    </style>    

</head>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<body>
    
<?php require_once TEMPLATE.'nav.php'; ?>

<div class="container py-5">

<?php 
if (isset($_POST['g-recaptcha-response'])){
    $recaptcha_secret = CAPTCHA_SECRETA;
    $response = $_POST['g-recaptcha-response'];
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}");
    $response_data = json_decode($verify);
    //if ($response_data->success) {
    if (1==1) {
        ?>
            <div class="container">
                <h1 class="text-center mb-5 coupon-title text-primary">¡Tus Cupones Exclusivos!</h1>
                <div class="row g-4">
                    <?php
                        $api_url = URL_API."discounts";
                        //$data = json_encode(["Product" => $_GET['Id']]);
                        $data='';
                        $data = json_decode(API($jwt,$api_url,$data,'GET'), true);
                        if ($data['status'] === 'success') {
                            foreach ($data['data'] as $discount) {
                            
                                $random = rand(1, 3);
                                switch($random) {
                                    case 1:
                                        $class = 'text-success';
                                        break;
                                    case 2:
                                        $class = 'text-danger';
                                        break;
                                    case 3:
                                        $class = 'text-warning';
                                        break;
                                }                
                                echo '
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card h-100 coupon-card p-4 text-center">
                                                <div class="card-body d-flex flex-column justify-content-between">
                                                    <div>
                                                        <h3 class="card-title coupon-title '.$class.' mb-3">'.$discount['Name'].'</h3>
                                                        <p class="card-text text-muted mb-4">'.$discount['Description'].'</p>
                                                        
                                                        <div class="coupon-code-container">
                                                            <input type="text" value="'.$discount['Code'].'" id="code1" class="d-none">
                                                            <span class="coupon-code">'.$discount['Code'].'</span>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-coupon w-100 mt-auto" onclick="guardarCupon('."'".$discount['Code']."'".', '."'".$discount['Type']."'".','."'".$discount['Amount']."'".')">
                                                        Aplicar Código
                                                    </button>                    
                                                </div>
                                            </div>
                                        </div>
                                ';
                            }        
                        } 
                    ?>    
                </div> 
            </div>        
        <?php
    }
    ?>
    <hr class="my-5">
    <?php require_once TEMPLATE.'allcat.php'; ?>    
    <?php
}
else{
    require_once TEMPLATE.'coupon.php';
}
?>

</div>

<?php require_once TEMPLATE.'social.php'; ?>

<?php require_once TEMPLATE.'cart.php'; ?>

<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>
<script src="<?php echo URL_BASE."/".TEMPLATE;?>js/idx-template.js"></script>
<script src="<?php echo URL_BASE."/";?>js/general.js"></script>
<script>

</script>
</body>
</html>