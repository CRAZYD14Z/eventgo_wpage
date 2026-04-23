<?php 
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 
?>
    <link rel="stylesheet" href="<?php echo URL_BASE."/";?>css/general.css">
    <link rel="stylesheet" href="<?php echo URL_BASE."/".TEMPLATE;?>css/coupon.css">
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <style>

    </style>
</head>



<body>

<?php require_once TEMPLATE.'nav.php'; ?>
<?php require_once TEMPLATE.'hero.php'; ?>

<div class="container py-5">
    <?php  require_once TEMPLATE.'cat.php'; ?>
    <hr class="my-5">
    <?php require_once TEMPLATE.'us.php'; ?>
    <hr class="my-5">
    <?php require_once TEMPLATE.'ref.php'; ?>        
    <hr class="my-5">
    <?php require_once TEMPLATE.'allcat.php'; ?>    
    <hr class="my-5">
    <?php require_once TEMPLATE.'eventype.php'; ?>
    <hr class="my-5">
    <?php require_once TEMPLATE.'loc.php'; ?>
    <hr class="my-5">
    <?php require_once TEMPLATE.'contact.php'; ?>

</div>
<?php require_once TEMPLATE.'social.php'; ?>

<?php require_once TEMPLATE.'couponcard.php'; ?>

<?php require_once TEMPLATE.'cart.php'; ?>

<?php require_once TEMPLATE.'scripts.php'; ?>

<script src="<?php echo TEMPLATE;?>js/idx-template.js"></script>
<?php require_once 'scripts.php'; ?>
<script src="js/general.js"></script>
<script>

var autoCloseTimer;

        $(document).ready(function() {
            // Mostrar la tarjeta tras 5 segundos
            setTimeout(function() {
                showCard();
            }, 5000);
        });

        function showCard() {
            $('#couponTab').hide();
            $('#couponCard').fadeIn('slow');

            // Auto-ocultado tras otros 5 segundos
            autoCloseTimer = setTimeout(function() {
                hideCardAndShowTab();
            }, 10000); 
        }

        function closeCardManually() {
            clearTimeout(autoCloseTimer);
            hideCardAndShowTab();
        }

        function showCardFromTab() {
            showCard();
        }

        function hideCardAndShowTab() {
            $('#couponCard').fadeOut('fast', function() {
                //$('#couponTab').fadeIn('slow');
                $('#couponTab').fadeIn('slow').addClass('animate-bounce');
            });
        }


</script>
</body>
</html>