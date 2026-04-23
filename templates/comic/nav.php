<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo URL_BASE?>">
            <img height="36px" src="<?php echo COMPANY_LOGO?>" alt="<?php echo COMPANY_NAME?>"><?php echo COMPANY_NAME?>
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
                <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE?>/categories">Categorías</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE?>/events">Eventos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo URL_BASE?>/coupon/get-coupon">Cupones</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropNosotros" data-bs-toggle="dropdown">
                        Acerca de nosotros
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/us">Nosotros</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL_BASE?>/contact">Contáctanos</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>