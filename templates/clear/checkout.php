    <div class="row g-5">
        
        <div class="col-lg-7">
            
            
            
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-3">
                        <h4 class="mb-4 fw-bold"><?= Trd(21) ?></h4>
                    </div>                

                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="fa-solid fa-user text-primary"></i>
                        </div>
                        <h5 class="mb-0 fw-bold"><?= Trd(22) ?></h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="hidden" id="Id">
                            <input type="hidden" id="Type">
                            <input type="hidden" id="Idgfc">
                            <input type="hidden" id="Agfc">
                            <label class="form-label small fw-bold"><?= Trd(23) ?></label>
                            <input type="text" id="nombre_cliente" class="form-control" >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(24) ?></label>
                            <input type="text" id="apellidos" class="form-control" >
                        </div>                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(25) ?></label>
                            <input type="text" id="organizacion" class="form-control" >
                        </div>                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(26) ?></label>
                            <input type="tel" id="tel_cliente" class="form-control" >
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><?= Trd(27) ?></label>
                            <input type="email" id="email_cliente" class="form-control" >
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold"><?= Trd(28) ?></label>
                            <input type="text" id="dir_cliente" class="form-control" >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(29) ?></label>
                            <input type="text" id="ciudad_cliente" class="form-control">
                        </div>
                        <?php if ($account['Pais'] == 'MX'){?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(30) ?></label>
                            <input type="text" id="colonia_cliente" class="form-control">
                        </div>
                        <?php }?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(31) ?></label>
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
                            <h5 class="mb-0 fw-bold"><?= Trd(32) ?></h5>
                            </div>
                        </div>
                        <div class="col-6">

                            <button type="button" id="btn_copiar_direccion" class="btn btn-primary btn-lg w-100 mt-4 rounded-pill fw-bold shadow">
                                <?= Trd(33) ?> <i class="fa-regular fa-copy"></i>
                            </button>                        

                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold"><?= Trd(28) ?></label>
                            <input type="text" id="dir_evento" class="form-control" >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(29) ?></label>
                            <input type="text" id="ciudad_evento" class="form-control">
                        </div>
                        <?php if ($account['Pais'] == 'MX'){?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(30) ?></label>
                            <input type="text" id="colonia_evento" class="form-control">
                        </div>
                        <?php }?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(31) ?></label>
                            <input type="text" id="cp_evento" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><?= Trd(34) ?></label>
                            <textarea id="ref_evento" class="form-control" rows="2" ></textarea>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(35) ?></label>
                            <select class="form-select" aria-label="Default select example" id="superficie">
                                <option value="" selected><?= Trd(36) ?></option>
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
                            <label class="form-label small fw-bold"><?= Trd(37) ?></label>

                            <select class="form-select" aria-label="Default select example" id="tipo_entrega">
                                <option value="" selected><?= Trd(36) ?></option>
                                <option value='1'><?= Trd(38) ?></option>
                            </select>                            

                        </div>

                        <div class="col-md-6">
                            <!--
                            <label class="form-label small fw-bold">Excluir Impuestos</label>
                            <input type="text" id="tax" class="form-control">
                            -->
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= Trd(39) ?></label>
                            <div class="input-group input-group-sm mb-3">
                                <input type="text" id="cupon" class="form-control">
                                    <button 
                                        class="btn btn-outline-secondary" 
                                        type="button" 
                                        id="btn-validar-cupon"
                                        onclick="validar_cupon()"
                                    >
                                        <?= Trd(40) ?>
                                    </button>
                            </div>                            
                        </div>                        

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="sticky-top" style="top: 2rem;z-index: 900;">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white py-3 border-0">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-receipt me-2"></i><?= Trd(41) ?></h5>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="p-4 bg-light border-bottom">
                            <div class="row text-center">
                                <div class="col-12 mb-3">
                                    <span class="d-block small text-muted text-uppercase fw-bold"><?= Trd(42) ?></span>
                                    <span id="resumen_fecha" class="fs-5 fw-bold text-primary"><?= Trd(43) ?></span>
                                </div>
                                <div class="col-6 border-end">
                                    <span class="d-block small text-muted text-uppercase fw-bold"><?= Trd(44) ?></span>
                                    <span id="resumen_hInicio" class="fw-bold">--:--</span>
                                </div>
                                <div class="col-6">
                                    <span class="d-block small text-muted text-uppercase fw-bold"><?= Trd(45) ?></span>
                                    <span id="resumen_hFin" class="fw-bold">--:--</span>
                                </div>
                            </div>
                        </div>

                        <div id="checkout_items" class="p-4" style="max-height: 400px; overflow-y: auto;">
                            </div>

                        <div class="p-4 bg-light border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted"><?= Trd(46) ?></span>
                                <span id="checkout_subtotal" class="fw-bold">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span id="chEtiquetaCupon" class="text-muted"><?= Trd(47) ?></span>
                                <span id="chDescuento" class="fw-bold">$0.00</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span  class="text-muted"><?= Trd(48) ?></span>
                                <span  class="fw-bold"></span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span id="" class="text-muted">
                                    <div class="input-group input-group-sm mb-3">
                                        <input 
                                            type="text" 
                                            id="gifcardcode" 
                                            class="form-control" 
                                            placeholder="XXXX-XXXX-XXXX-XXXX" 
                                            maxlength="20"
                                        >
                                        <button 
                                            class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="btn-validar"
                                        >
                                            <?= Trd(49) ?>
                                        </button>
                                    </div>
                                </span>
                                <span id="chTarjetaRegalo" class="fw-bold">$0.00</span>
                            </div>                            

                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 fw-bold"><?= Trd(50) ?></span>
                                <div class="text-end">
                                    <span id="checkout_total" class="h4 mb-0 fw-bold text-primary">$0.00</span>
                                    <small class="d-block text-muted" style="font-size: 0.6rem;"><?= Trd(51) ?></small>
                                </div>
                            </div>
                            
                            <button type="button" id="btn_enviar_cotizacion" class="btn btn-primary btn-lg w-100 mt-4 rounded-pill fw-bold shadow">
                                <?= Trd(52) ?> <i class="fa-solid fa-paper-plane ms-2"></i>
                            </button>
                            <p class="text-center mt-3 small text-muted"><i class="fa-solid fa-lock me-1"></i> <?= Trd(53) ?></p>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>