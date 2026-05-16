<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "nav"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?>        
<nav class="navbar navbar-expand-lg navbar-dark  sticky-top shadow" style="background-color:  #ff1642;">
    <div class="container">
        
        <a class="navbar-brand fw-bold" href="<?php echo URL_BASE?>">
            <div class="logo">
            <img height="80px" src="https://gaxibrincolines.com/images/logo.png" alt="<?php echo COMPANY_NAME?>"><?php echo COMPANY_NAME?>
            </div>
        </a>

        <div class="d-flex align-items-center ms-auto me-3 order-lg-last">
            <a class="nav-link position-relative p-2" href="#" id="cartIcon" style="color: white;">
                <i class="fas fa-shopping-cart fa-lg" onclick=" $('#modalReserva').modal('show');"></i>
                <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                    0
                </span>
            </a>            
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE?>/categories"><?= Trd(1) ?></a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE?>/events"><?= Trd(2) ?></a></li>
                <?php if ( $account['Couppon'] == 1){?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE?>/coupon/get-coupon"><?= Trd(3) ?></a></li>
                <?php }?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropNosotros" data-bs-toggle="dropdown">
                        <?= Trd(4) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="background-color:  #ff1642;">
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/us"><?= Trd(5) ?></a></li>
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/contact"><?= Trd(6) ?></a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropNosotros" data-bs-toggle="dropdown">
                        <i class="fas fa-globe"></i> <?php echo strtoupper($_SESSION['Idioma'])?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="background-color:  #ff1642;">
                        <li><a class="dropdown-item lang-option" href="#" data-lang="es"><?= Trd(7) ?></a></li>
                        <li><a class="dropdown-item lang-option" href="#" data-lang="en"><?= Trd(8) ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>