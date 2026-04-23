<style>
    /* Estilos específicos para el Modal Lateral Comic */
    #modalReserva .modal-content {
        background: var(--paper);
        border-left: 5px solid var(--ink) !important;
        background-image: radial-gradient(var(--paper2) 2px, transparent 0);
        background-size: 15px 15px; /* Efecto puntos Ben-Day */
    }

    #modalReserva .modal-header {
        background: var(--ink) !important;
        border-bottom: 4px solid var(--blue);
        color: var(--yellow);
    }

    #modalReserva .modal-title {
        font-family: 'Bangers', cursive;
        letter-spacing: 2px;
        font-size: 1.8rem;
        color: var(--yellow) !important;
    }

    #modalReserva .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 1;
    }

    .comic-section {
        background: var(--white);
        border: 3px solid var(--ink);
        box-shadow: 5px 5px 0 var(--ink);
        margin: 15px;
    }

    .comic-label-small {
        font-family: 'Bangers', cursive;
        color: var(--ink);
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    .comic-select {
        border: 2px solid var(--ink) !important;
        border-radius: 0 !important;
        font-family: 'Comic Neue', cursive;
        font-weight: 800;
        box-shadow: 2px 2px 0 var(--ink);
    }

    .modal-footer.comic-footer {
        background: var(--white);
        border-top: 5px solid var(--ink);
    }

    .total-badge {
        font-family: 'Bangers', cursive;
        font-size: 2.2rem;
        color: var(--blue);
        line-height: 1;
        -webkit-text-stroke: 1px var(--ink);
    }

    .btn-checkout {
        background: var(--red) !important;
        color: white !important;
        font-family: 'Bangers', cursive;
        font-size: 1.6rem;
        letter-spacing: 2px;
        border: 4px solid var(--ink) !important;
        box-shadow: 6px 6px 0 var(--ink) !important;
        border-radius: 0 !important;
        transition: 0.1s;
    }

    .btn-checkout:hover {
        transform: scale(1.02) rotate(-1deg);
        background: var(--blue) !important;
    }

    .btn-action-sm {
        font-family: 'Bangers', cursive;
        border: 2px solid var(--ink);
        border-radius: 0;
        background: var(--yellow);
        box-shadow: 3px 3px 0 var(--ink);
    }
</style>

<div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 450px; margin-right: 0; margin-top: 0; margin-bottom: 0; height: 100%;">
        <div class="modal-content border-0 shadow-lg" style="height: 100vh; border-radius: 0;">
            
            <div class="modal-header py-3">
                <h5 class="modal-title"><i class="fa-solid fa-cart-shopping me-2"></i>MI RESERVACIÓN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                
                <div class="comic-section p-3">
                    <div id="resumenFecha" class="d-flex justify-content-between align-items-center d-none">
                        <div>
                            <span class="comic-label-small d-block">Periodo Seleccionado</span>
                            <span id="textoFechaRango" class="fw-bold text-primary fs-5">-- / -- / --</span>
                        </div>
                        <button type="button" class="btn btn-action-sm btn-sm" id="btnReabrirCalendario">
                            CAMBIAR <i class="fa-regular fa-calendar-check"></i>
                        </button>
                        <input type="hidden" id="FI">
                        <input type="hidden" id="FF">                        
                    </div>

                    <div id="contenedorCalendario">
                        <label class="comic-label-small d-block mb-2 text-center">Seleccione el Rango de Misión</label>
                        <input type="text" id="calendarioRango" class="form-control text-center comic-input mb-3 d-none" placeholder="FECHAS...">
                        <button type="button" id="btnConfirmarFecha" class="btn btn-dark w-100 fw-bold comic-label-small py-2 shadow-sm" style="border-radius:0">
                            SELECCIONAR FECHA
                        </button>

                    </div>
                </div>

                <div class="comic-section p-3 mb-3">
                    <div class="row g-2 text-center">
                        <div class="col-6 border-end border-dark">
                            <label class="comic-label-small text-primary d-block mb-1">Hora Inicio</label>
                            <select id="hInicio" class="form-select form-select-sm comic-select bg-light text-center"></select>
                        </div>
                        <div class="col-6">
                            <label class="comic-label-small text-success d-block mb-1">Hora Término</label>
                            <select id="hFin" class="form-select form-select-sm comic-select bg-light text-center"></select>
                        </div>
                    </div>
                </div>

                <div class="px-3 pb-4" id="cart-items-container">
                    </div>
            </div>

            <div class="modal-footer flex-column comic-footer p-4">
                <div class="w-100 mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span id="Articulos" class="fw-bold text-uppercase small" style="font-family:'Comic Neue'">Subtotal (0 artículos)</span>
                        <span id="SubTotal" class="fw-bold">$0.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span id="EtiquetaCupon" class="text-danger fw-bold small" style="font-family:'Comic Neue'">DESCUENTO</span>
                        <span id="Descuento" class="fw-bold text-danger">-$0.00</span>
                    </div>                    

                    <div class="d-flex justify-content-between align-items-end border-top border-dark pt-3">
                        <span class="comic-label-small fs-4">TOTAL FINAL</span>
                        <div class="text-end">
                            <span id="total_monto" class="total-badge">$2,100.00</span>
                            <small class="d-block text-muted fw-bold" style="font-size: 0.6rem;">MXN (IVA Incluido)</small>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="btnConfirmar" class="btn btn-checkout w-100 py-3 mt-2" onclick="document.location='<?php echo URL_BASE?>/checkout'">
                    ¡ORDENAR AHORA! <i class="fa-solid fa-bolt ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>