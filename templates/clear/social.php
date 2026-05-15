    <?php 
        $api_url = URL_API."Traducciones_web";
        $data = json_encode(['program' => "social"]);
        $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
    ?> 

    <footer class="py-5 text-center border-top">
        <h4 class="mb-4 fw-bold"><?= Trd(1) ?></h4>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?php echo URLFace?>" class="btn btn-outline-primary rounded-circle p-3 shadow-sm" style="width: 60px; height: 60px;">
                <i class="fab fa-facebook-f fa-lg"></i>
            </a>
            <a href="<?php echo URLX?>" class="btn btn-outline-dark rounded-circle p-3 shadow-sm" style="width: 60px; height: 60px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M453.2 112L523.8 112L369.6 288.2L551 528L409 528L297.7 382.6L170.5 528L99.8 528L264.7 339.5L90.8 112L236.4 112L336.9 244.9L453.2 112zM428.4 485.8L467.5 485.8L215.1 152L173.1 152L428.4 485.8z"/></svg>                
            </a>            
            <a href="<?php echo URLInsta?>" class="btn btn-outline-danger rounded-circle p-3 shadow-sm" style="width: 60px; height: 60px;">
                <i class="fab fa-instagram fa-lg"></i>
            </a>
            <a href="<?php echo URLWhats?>" class="btn btn-outline-success rounded-circle p-3 shadow-sm" style="width: 60px; height: 60px;">
                <i class="fab fa-whatsapp fa-lg"></i>
            </a>
            <a href="<?php echo URLLink?>" class="btn btn-outline-info rounded-circle p-3 shadow-sm" style="width: 60px; height: 60px;">
                <i class="fab fa-linkedin-in fa-lg"></i>
            </a>
            <a href="<?php echo URLYou?>" class="btn btn-outline-danger rounded-circle p-3 shadow-sm" style="width: 60px; height: 60px;">
                <i class="fab fa-youtube fa-lg"></i>
            </a>            
        </div>
        <p class="mt-4 text-muted small">&copy; 2026 <?php echo COMPANY_NAME?>. <?= Trd(2) ?></p>
    </footer>  

<!-- Modal Dinámico -->
<div class="modal fade" id="modalAlerta" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div id="modalHeaderColor" class="py-2" style="height: 4px;"></div> <!-- Barra de color superior -->
      <div class="modal-body text-center py-4">
        <div id="modalIcono" class="fs-1 mb-2"></div> <!-- Aquí va el icono -->
        <h6 id="modalTitulo" class="fw-bold text-uppercase"></h6>
        <p id="mensajeAlerta" class="mb-0 text-muted small"></p>
      </div>
      <div class="modal-footer border-0 pt-0 justify-content-center">
        <button type="button" class="btn btn-sm px-4" id="btnCerrarModal" data-bs-dismiss="modal"><?= Trd(3) ?></button>
      </div>
    </div>
  </div>
</div>    