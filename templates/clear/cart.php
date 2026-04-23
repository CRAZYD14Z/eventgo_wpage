<div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 450px; margin-right: 0; margin-top: 0; margin-bottom: 0; height: 100%;">
        <div class="modal-content border-0 shadow-lg" style="height: 100vh; border-radius: 0;">
            
            <div class="modal-header bg-white border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-cart3 me-2"></i>Mi Reservación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0 bg-light">
                
                <div class="bg-white border-bottom shadow-sm">
                    <div id="resumenFecha" class="p-3 d-flex justify-content-between align-items-center d-none">
                        <div>
                            <span class="small text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Periodo Seleccionado</span>
                            <span id="textoFechaRango" class="fw-bold text-primary">-- / -- / --</span>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm shadow-sm" id="btnReabrirCalendario">
                            SELECCIONAR FECHA <i class="fa-regular fa-calendar-check"></i>
                        </button>
                        <input type="hidden" id="FI">
                        <input type="hidden" id="FF">
                    </div>

                    <div id="contenedorCalendario" class="p-3">
                        <label class="small fw-bold text-muted text-uppercase d-block mb-2 text-center">Seleccione el Rango</label>
                        <div class="d-flex justify-content-center mb-3">
                            <input type="text" id="calendarioRango" class="form-control text-center border-0 fw-bold fs-5 shadow-none d-none" placeholder="Seleccione fechas">

                        </div>
                        <button type="button" id="btnConfirmarFecha" class="btn btn-dark w-100 fw-bold py-2 shadow-sm rounded-3">
                            SELECCIONAR FECHA
                        </button>
                    </div>
                </div>

                <div class="bg-white p-3 border-bottom mb-3 shadow-sm">
                    <div class="row g-2 text-center">
                        <div class="col-6 border-end">
                            <label class="small fw-bold text-primary text-uppercase d-block mb-1" style="font-size: 0.65rem;">Hora Inicio</label>
                            <select id="hInicio" class="form-select form-select-sm border-0 bg-light text-center fw-bold rounded-3"></select>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-success text-uppercase d-block mb-1" style="font-size: 0.65rem;">Hora Término</label>
                            <select id="hFin" class="form-select form-select-sm border-0 bg-light text-center fw-bold rounded-3"></select>
                        </div>
                    </div>
                </div>

                <div class="px-3 pb-4">

                </div>
            </div>

            <div class="modal-footer flex-column bg-white border-top p-3 shadow-lg">
                <div class="w-100 mb-3">
                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span id="Articulos">Subtotal (0 artículos)</span>
                        <span id="SubTotal">$0.00</span>
                    </div>

                        <input type="hidden" id="CUPON">
                        <input type="hidden" id="TIPOCUPON">
                        <input type="hidden" id="DESCUENTO">


                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span id="EtiquetaCupon">Descuento ( No Aplicado )</span>
                        <span id="Descuento">$0.00</span>
                    </div>                    

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span class="text-primary text-end">$2,100.00<br><small class="text-muted fw-normal" style="font-size: 0.7rem;">MXN (IVA Incluido)</small></span>
                    </div>
                </div>
                <button type="button" id="btnConfirmar" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow" onclick="document.location='<?php echo URL_BASE?>/checkout'">
                    CHECKOUT
                </button>
            </div>
        </div>
    </div>
</div>