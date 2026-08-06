<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "nav"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?>        
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <!-- Logo y Nombre de la Empresa -->
        <a class="navbar-brand" href="<?php echo URL_BASE?>">
            <img height="32px" src="<?php echo COMPANY_LOGO?>" alt="<?php echo COMPANY_NAME?>" class="me-2 rounded-circle" style="border:3px solid rgba(255,255,255,.3);">
            <?php echo COMPANY_NAME?>
        </a>

        <!-- Carrito de Compras (Con estilos lego-yellow y disparador de Modal integrado) -->
        <div class="d-flex align-items-center ms-auto me-3 order-lg-last">
            <a class="nav-link position-relative p-2" href="#" id="cartIcon" onclick="$('#modalReserva').modal('show');">
                <i class="fas fa-shopping-cart fa-lg" style="color:var(--lego-yellow);"></i>
                <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="font-size:.65rem;">
                    0
                </span>
            </a>
        </div>

        <!-- Botón Toggler para Móviles -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" style="color:var(--lego-yellow);">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menú de Navegación -->
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto">
                <!-- Icono de Inicio / Home -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL_BASE?>/"><i class="fa-solid fa-house"></i></a>
                </li>                    
                
                <!-- Categorías -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL_BASE?>/categories">🟦 <?= Trd(1) ?></a>
                </li>
                
                <!-- Eventos -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL_BASE?>/events">🟩 <?= Trd(2) ?></a>
                </li>
                
                <!-- Cupones (Condicional dinámico) -->
                <?php if ( $account['Couppon'] == 1){?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URL_BASE?>/coupon/get-coupon">🎟️ <?= Trd(3) ?></a>
                    </li>
                <?php }?>
                
                <!-- Dropdown Nosotros -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropNosotros" data-bs-toggle="dropdown">🟨 <?= Trd(4) ?></a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/us">🟥 <?= Trd(5) ?></a></li>
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/contact">📨 <?= Trd(6) ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropNosotros" data-bs-toggle="dropdown">
                        Templates
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/index.php?Template=clear">Clear</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/index.php?Template=acuatico">Acuatic</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/index.php?Template=circo">Circus</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/index.php?Template=comic">Comic</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/index.php?Template=cumpleanos">Birthday</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/index.php?Template=gaxi">Gaxi</a></li>
                        <li><a class="dropdown-item" href="https://www.eventgodemo.solutions/">DsJumpers</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/index.php?Template=lego">Lego</a></li>
                    </ul>
                </li>                   

                <!-- Dropdown Selección de Idioma -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropIdiomas" data-bs-toggle="dropdown">
                        <i class="fas fa-globe"></i> <?php echo strtoupper($_SESSION['Idioma'])?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item lang-option" href="#" data-lang="es"><?= Trd(7) ?></a></li>
                        <li><a class="dropdown-item lang-option" href="#" data-lang="en"><?= Trd(8) ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>