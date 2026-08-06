    <?php 
        $api_url = URL_API."Traducciones_web";
        $data = json_encode(['program' => "social"]);
        $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
    ?> 

<footer>
    <h4 class="mb-4">🧱 ¡<?= Trd(1) ?>!</h4>
    <div class="d-flex justify-content-center gap-3 flex-wrap mb-3">
        <a href="<?php echo URLFace?>" class="social-btn"><i class="fab fa-facebook-f"></i></a>
        <a href="<?php echo URLX?>" class="social-btn"><i class="fab fa-twitter"></i></a>
        <a href="<?php echo URLInsta?>" class="social-btn"><i class="fab fa-instagram"></i></a>
        <a href="<?php echo URLWhats?>" class="social-btn"><i class="fab fa-whatsapp"></i></a>
        <a href="<?php echo URLLink?>" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
    </div>
    <p>&copy; 2026 — Mi Empresa — Todos los derechos reservados</p>
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