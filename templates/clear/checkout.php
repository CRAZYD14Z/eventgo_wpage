    <div class="row g-5">
        
        <div class="col-lg-7">
            <h4 class="mb-4 fw-bold">Confirmar Detalles de Cotización</h4>
            
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="fa-solid fa-user text-primary"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Información de Contacto</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nombre</label>
                            <input type="text" id="nombre_cliente" class="form-control" >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Apellidos</label>
                            <input type="text" id="apellidos" class="form-control" >
                        </div>                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Organización</label>
                            <input type="text" id="organizacion" class="form-control" >
                        </div>                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Teléfono / WhatsApp</label>
                            <input type="tel" id="tel_cliente" class="form-control" >
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Correo Electrónico</label>
                            <input type="email" id="email_cliente" class="form-control" >
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Dirección Completa (Calle y Número)</label>
                            <input type="text" id="dir_cliente" class="form-control" >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Ciudad</label>
                            <input type="text" id="ciudad_cliente" class="form-control">
                        </div>                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Colonia</label>
                            <input type="text" id="colonia_cliente" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Código Postal</label>
                            <input type="text" id="cp_cliente" class="form-control">
                        </div>

                    </div>                    
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fa-solid fa-location-dot text-success"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Ubicación del Evento</h5>
                            </div>
                        </div>
                        <div class="col-6">

                            <button type="button" id="btn_copiar_direccion" class="btn btn-primary btn-lg w-100 mt-4 rounded-pill fw-bold shadow">
                                Copiar de contacto <i class="fa-regular fa-copy"></i>
                            </button>                        

                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Dirección Completa (Calle y Número)</label>
                            <input type="text" id="dir_evento" class="form-control" >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Ciudad</label>
                            <input type="text" id="ciudad_evento" class="form-control">
                        </div>                          
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Colonia</label>
                            <input type="text" id="colonia_evento" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Código Postal</label>
                            <input type="text" id="cp_evento" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Referencias / Instrucciones</label>
                            <textarea id="ref_evento" class="form-control" rows="2" ></textarea>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Superficie</label>
                            <select class="form-select" aria-label="Default select example" id="superficie">
                                <option value="" selected>Seleccionar</option>
                                <?php
                                    $api_url = URL_API."surfaces";
                                    //$data = json_encode(["Product" => $_GET['Id']]);
                                    $data='';
                                    $data = json_decode(API($jwt,$api_url,$data,'POST'), true);
                                    if ($data['status'] === 'success') {
                                        foreach ($data['data'] as $surface) {
                                            echo "<option value='".$surface['Id']."'>".$surface['Nombre']."</option>";
                                        }        
                                    } 
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tipo Entrega</label>

                            <select class="form-select" aria-label="Default select example" id="tipo_entrega">
                                <option value="" selected>Seleccionar</option>
                                <option value='1'>ON SITE</option>
                            </select>                            

                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Excluir Impuestos</label>
                            <input type="text" id="tax" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Codigo Cupón</label>
                            <input type="text" id="cupon" class="form-control">
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="sticky-top" style="top: 2rem;z-index: 900;">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white py-3 border-0">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-receipt me-2"></i>Resumen</h5>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="p-4 bg-light border-bottom">
                            <div class="row text-center">
                                <div class="col-12 mb-3">
                                    <span class="d-block small text-muted text-uppercase fw-bold">Fecha del Evento</span>
                                    <span id="resumen_fecha" class="fs-5 fw-bold text-primary">Cargando...</span>
                                </div>
                                <div class="col-6 border-end">
                                    <span class="d-block small text-muted text-uppercase fw-bold">Entrega</span>
                                    <span id="resumen_hInicio" class="fw-bold">--:--</span>
                                </div>
                                <div class="col-6">
                                    <span class="d-block small text-muted text-uppercase fw-bold">Recolección</span>
                                    <span id="resumen_hFin" class="fw-bold">--:--</span>
                                </div>
                            </div>
                        </div>

                        <div id="checkout_items" class="p-4" style="max-height: 400px; overflow-y: auto;">
                            </div>

                        <div class="p-4 bg-light border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal Artículos</span>
                                <span id="checkout_subtotal" class="fw-bold">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span id="chEtiquetaCupon" class="text-muted">Descuento ( No Aplicado )</span>
                                <span id="chDescuento" class="fw-bold">$0.00</span>
                            </div>                            
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 fw-bold">Total Final</span>
                                <div class="text-end">
                                    <span id="checkout_total" class="h4 mb-0 fw-bold text-primary">$0.00</span>
                                    <small class="d-block text-muted" style="font-size: 0.6rem;">MXN - IVA Incluido</small>
                                </div>
                            </div>
                            
                            <button type="button" id="btn_enviar_cotizacion" class="btn btn-primary btn-lg w-100 mt-4 rounded-pill fw-bold shadow">
                                ENVIAR COTIZACIÓN <i class="fa-solid fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <p class="text-center mt-3 small text-muted">
                    <i class="fa-solid fa-lock me-1"></i> Tu información está segura
                </p>
            </div>
        </div>
    </div>