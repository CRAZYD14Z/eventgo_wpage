    <?php 
        $api_url = URL_API."Traducciones_web";
        $data = json_encode(['program' => "social"]);
        $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
    ?> 

<footer>
    <h4 class="mb-4">🎪 <?= Trd(1) ?> 🎪</h4>
    <div class="d-flex justify-content-center gap-3 flex-wrap mb-4">
        <a href="<?php echo URLFace?>" class="social-btn" title="Facebook"><i class="fab fa-facebook-f fa-lg"></i></a>
        <a href="<?php echo URLX?>" class="social-btn" title="Twitter"><i class="fab fa-twitter fa-lg"></i></a>
        <a href="<?php echo URLInsta?>" class="social-btn" title="Instagram"><i class="fab fa-instagram fa-lg"></i></a>
        <a href="<?php echo URLWhats?>" class="social-btn" title="WhatsApp"><i class="fab fa-whatsapp fa-lg"></i></a>
        <a href="<?php echo URLLink?>" class="social-btn" title="LinkedIn"><i class="fab fa-linkedin-in fa-lg"></i></a>
    </div>
    <p class="mt-2 text-muted small">&copy; 2026 🎠 Tu Empresa. Todos los derechos reservados. 🎠</p>
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