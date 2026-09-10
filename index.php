<?php
    ob_start();
    session_start();
    require 'vendor/autoload.php';
    //if (isset($_GET['Template']))
    //    $_SESSION['Template'] = $_GET['Template'];
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 

    $metaTitle = COMPANY_NAME;
    $metaDescription = trim(preg_replace('/\s+/', ' ', strip_tags(NOSOTROS ?? '')));
    if ($metaDescription === '') {
        $metaDescription = 'Servicios y productos para tus eventos.';
    }
    $metaUrl = rtrim(URL_BASE, '/').'/';
    $socialProfiles = array_values(array_filter([
        defined('URLFace') ? URLFace : '',
        defined('URLX') ? URLX : '',
        defined('URLInsta') ? URLInsta : '',
        defined('URLYou') ? URLYou : '',
        defined('URLLink') ? URLLink : ''
    ]));
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $metaTitle,
        'description' => $metaDescription,
        'url' => $metaUrl,
        'logo' => COMPANY_LOGO
    ];
    if ($socialProfiles) {
        $schema['sameAs'] = $socialProfiles;
    }
?>
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($metaUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars(COMPANY_LOGO, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars(COMPANY_LOGO, ENT_QUOTES, 'UTF-8') ?>">
    <script type="application/ld+json">
        <?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
    </script>
    <link rel="stylesheet" href="<?php echo URL_BASE."/";?>css/general.css">
    <link rel="stylesheet" href="<?php echo URL_BASE."/".TEMPLATE;?>css/coupon.css">
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
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
<?php 
    if ( $account['Couppon'] == 1){
        require_once TEMPLATE.'couponcard.php'; 
    }
?>
<?php require_once TEMPLATE.'cart.php'; ?>
<?php require_once TEMPLATE.'scripts.php'; ?>
<script src="<?php echo TEMPLATE;?>js/idx-template.js"></script>
<?php require_once 'scripts.php'; ?>
<script src="js/general.php"></script>
<script>
<?php if ( $account['Couppon'] == 1){?>
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
<?php } ?>

</script>
</body>
</html>