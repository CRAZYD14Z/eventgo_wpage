<?php 
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 
?>
    <script type="text/javascript" src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
    <script type='text/javascript' src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>        
    <link rel="stylesheet" href="css/general.css">
    <style>

        .card-payment { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .form-control { border-radius: 8px; padding: 12px; border: 1px solid #dee2e6; }
        .form-control:focus { box-shadow: none; border-color: #000; }
        .btn-pay { background: #000; color: #fff; border: none; padding: 14px; border-radius: 8px; font-weight: 600; transition: 0.3s; }
        .btn-pay:hover { background: #333; color: #fff; }
        .btn-pay:disabled { background: #ccc; }
        .input-group-text { background: transparent; border-radius: 8px; }
        .anticipo-card { cursor: pointer; border: 2px solid #eee; border-radius: 10px; transition: 0.2s; }
        .anticipo-card:hover { border-color: #000; }
        .selected-anticipo { border-color: #000 !important; background-color: #f8f9fa; }
        .text-detail { font-size: 0.85rem; color: #6c757d; }


#pdf-canvas {
    max-height: 160px; /* Limita la altura del preview */
    object-fit: contain;
    background-color: #eee;
}

.anticipo-card {
    cursor: pointer;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    transition: all 0.2s;
}

.selected-anticipo {
    border-color: #0d6efd;
    background-color: #f0f7ff;
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.2);
}        

.spinner-border {
    --bs-spinner-width: 1.2rem;
    --bs-spinner-height: 1.2rem;
    vertical-align: middle;
    margin-right: 8px;
}

    </style>
</head>
<body>

<?php require_once TEMPLATE.'nav.php'; ?>




<?php
        if (!isset($_GET['Id'])){
            echo "Enlace no válido.";
            die();
        }

        //$PayPlatform ='OPAY';
        $PayPlatform = 'SQUARE';

        $token = $_GET['Id']; // El UUID de la URL
        $ahora = date("Y-m-d H:i:s");


        $api_url = URL_API."quotes";
        $data = json_encode(['token' => $token]);
        $cotizacion = json_decode(API($jwt,$api_url,$data,'GET'), true);

        if ($cotizacion) {
            // Verificar si la fecha actual es mayor a la de expiración
            if ($ahora > $cotizacion['ExpDate']) {
                echo "Lo sentimos, esta cotización ha caducado el " . $cotizacion['ExpDate']." $ahora";
                die();
            }
        } else {
            echo "Enlace no válido.";
            die();
        }


        $ID_OPAY='mles9ufd4m3rlilw00i8';
        $SK_OPAY='sk_ab545fdf98b446e78ed7ef908d1687a2';
        $PK_OPAY='pk_ed306f11c3764a9da955092ee7350160';

        $APPID_SQUARE='sandbox-sq0idb-yPqsgRsRdPMdHTk7zApWPg';
        $LOCID_SQUARE='LZZBFCYEW6Y2M';
        $TOKEN_SQUARE='EAAAl9cDwyU4FQwzfkJ8ge4kEYjsThSgsR6Cww34jRhn6ayhdnsB6S26ajhcf6b4';

        define('id_OPAY', $ID_OPAY);
        define('sk_OPAY', $SK_OPAY);
        define('pk_OPAY', $PK_OPAY);

        define('appId_square', $APPID_SQUARE);
        define('locId_square', $LOCID_SQUARE);
        define('accessToken_square',$TOKEN_SQUARE);        


        $api_url = URL_API."quote_account";
        //$data = json_encode(['token' => $token]);
        $data ='';
        $account = json_decode(API($jwt,$api_url,$data,'GET'), true);

        //$api_url = URL_API."tip_deposit";
        //$data = json_encode(['tip' => $Tip,'apay' => $APay,'quote' => $cotizacion['IdQuote']]);
        //$Tips = json_decode(API($jwt,$api_url,$data,'POST'), true);

        $api_url = URL_API."quote_data";
        $data = json_encode(['lead' => $cotizacion['IdQuote']]);        
        $respuesta = json_decode(API($jwt,$api_url,$data,'GET'), true);

        $lead = $respuesta['lead'];
        $lead_details =  $respuesta['lead_details'];
        $customer = $respuesta['customer'];        
        $organization =  $respuesta['organization'];
        $venue =  $respuesta['venue'];
        $discounts =  $respuesta['discounts'];    

        if ($lead['Customer'] > 0){
            $Nom =$customer['Nombres'];
            $Ape =$customer['Apellidos'];
            $Correo = $customer['Correo'];        

        }
        else{
            $Nom =$organization['Nombre'];
            $Ape ='';
            $Correo = $organization['Correo'];
        }
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-payment p-4">
                <h4 class="mb-4 fw-bold text-center">Checkout</h4>
                
                <form id="payment-form" action="#" method="POST">
                    <input type="hidden" name="token_id" id="token_id">
                    <input type="hidden" name="token" id="token" value="<?php echo $token ?>">
                    <input type="hidden" name="amount" id="monto-final" value="<?php echo $lead['DepositAmount']?>">

                    <div class="mb-4">                    
                        <h6 class="fw-bold mb-3">Contrato</h6>
                        
                        <div class="row g-3 align-items-center bg-light p-3 rounded border">
                            <div class="col-5 col-sm-4">
                                <div class="position-relative border bg-white rounded overflow-hidden shadow-sm" style="min-height: 120px;">
                                    <canvas id="pdf-canvas" class="w-100 d-block"></canvas>
                                    <div id="loader-pdf" class="position-absolute top-50 start-50 translate-middle">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-7 col-sm-8">
                                <p class="mb-1 fw-bold text-truncate" id="pdf-name">Contrato de Servicios</p>
                                <p class="mb-2 text-muted small">
                                    <i class="bi bi-file-earmark-pdf"></i> Documento legal listo para revisión.
                                </p>
                                <a href="<?php echo $token;?>.pdf" class="btn btn-outline-primary btn-sm" download>
                                    <i class="bi bi-download"></i> Descargar PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Información del Cliente</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="text" class="form-control" name="name" placeholder="Nombre" value="<?php echo $Nom;?>" required>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control" name="last_name" placeholder="Apellidos" value="<?php echo $Ape;?>" required>
                            </div>
                            <div class="col-12">
                                <input type="email" class="form-control" name="email" placeholder="correo@ejemplo.com" value="<?php echo $Correo;?>" required>
                            </div>
                        </div>
                    </div>


                    <div class="mb-4">
                        <?php if ($PayPlatform == 'OPAY'){?>
                        <h6 class="fw-bold mb-3">Datos de Tarjeta</h6>
                        <div class="mb-2">
                            <input type="text" class="form-control" placeholder="Nombre en la tarjeta" data-openpay-card="holder_name">
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control only-numbers" placeholder="Número de tarjeta" data-openpay-card="card_number" maxlength="16">
                        </div>
                        <div class="row g-2">
                            <div class="col-4">
                                <input type="text" class="form-control only-numbers" placeholder="Mes (MM)" data-openpay-card="expiration_month" maxlength="2">
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control only-numbers" placeholder="Año (AA)" data-openpay-card="expiration_year" maxlength="2">
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control only-numbers" placeholder="CVV" data-openpay-card="cvv2" maxlength="4">
                            </div>
                        </div>
                        <?php }
                        else{
                        ?>
                        <h6 class="fw-bold mb-3">Datos de Tarjeta</h6>
                            <div id="card-container" class="mb-3"></div>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2 opacity-75">
                        <span class="text-muted">Monto Total:</span>
                        <span class="fw-bold">$<?php echo number_format($lead['Total'], 2, '.', ',') ;?></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Saldo pendiente (después del pago):</span>
                        <span class="fw-bold">$<?php echo number_format($lead['Balance'], 2, '.', ',') ;?></span>
                    </div>

                    <hr>

                    <div class="p-3 bg-light border rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold d-block text-primary">Monto a pagar hoy</span>
                                <small class="text-muted">Por concepto de anticipo</small>
                            </div>
                            <h2 class="fw-bold mb-0 text-primary" id="display-pago-hoy">$<?php echo number_format($lead['DepositAmount'], 2, '.', ',') ;?></h2>
                        </div>
                    </div>
                    <div id="payment-status-container" class="mt-3 text-center">

                    </div>

                    <?php if ($PayPlatform == 'OPAY'){?>
                        <button class="btn btn-pay w-100" id="pay-button">Confirmar Pago</button>
                    <?php }
                        else{
                    ?>
                        <button id="card-button" type="button" class="btn btn-primary w-100 py-2">Confirmar Pago</button>
                    <?php
                    }
                    ?>
                    <div class="text-center mt-3">

                    <?php if ($PayPlatform == 'OPAY'){?>
                          <img src="https://www.openpay.mx/_ipx/_/img/header/openpay-color.svg" alt="Openpay" style="height: 25px; opacity: 0.6;">
                    <?php }
                        else{
                    ?>
                          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3d/Square%2C_Inc._logo.svg/1280px-Square%2C_Inc._logo.svg.png" alt="Square" style="height: 25px; opacity: 0.6;">
                    <?php
                    }
                    ?>                    

                      
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<?php if ($PayPlatform == 'OPAY'){
        $api_url = URL_API."OPAY";
        $data = '';
        $opay_account = json_decode(API($jwt,$api_url,$data,'GET'), true);    
    ?>
    <script>
        $(document).ready(function() {
            // Configuración Openpay
            OpenPay.setId('<?php echo $opay_account['Id'];?>');
            OpenPay.setApiKey('<?php echo $opay_account['PublicKey'];?>');
            OpenPay.setSandboxMode(true);
            OpenPay.deviceData.setup("payment-form", "deviceIdHiddenFieldName");

            // --- VALIDACIONES DE INPUTS ---
            $('.only-numbers').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Elimina cualquier cosa que no sea número
            });

            // --- PROCESAR PAGO ---
            $('#pay-button').on('click', function(e) {
                e.preventDefault();
                
                // 1. Referencia al botón para feedback visual
                var $btn = $(this);
                $btn.prop("disabled", true).text("Procesando...");

                // 2. Validación manual de campos requeridos (Nombre, Email, etc.)
                let valid = true;
                $('#payment-form input[required]').each(function() {
                    if ($(this).val().trim() === "") {
                        $(this).addClass('is-invalid'); // Clase de Bootstrap para error
                        valid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!valid) {
                    alert("Por favor completa los datos del cliente marcados como obligatorios.");
                    $btn.prop("disabled", false).text("Confirmar Pago");
                    return;
                }
                const statusDiv = document.getElementById('payment-status-container');
                statusDiv.innerHTML = ""; // Limpiar mensajes previos
                // 3. Crear Token con Openpay
                // extractFormAndCreate lee automáticamente los campos con 'data-openpay-card'
                OpenPay.token.extractFormAndCreate('payment-form', function(res) {
                    // --- CASO ÉXITO: Token generado ---
                    var token_id = res.data.id;
                    $('#token_id').val(token_id);

                    // Enviamos los datos al backend.php mediante AJAX
                    var datosFormulario = $('#payment-form').serialize();



                    $.ajax({
                        type: "POST",
                        url: url_api +'processpayment',
                    // IMPORTANTE: Convierte tu objeto a una cadena JSON
                        data:JSON.stringify(Object.fromEntries(new URLSearchParams(datosFormulario))),
                        dataType: "json",
                        contentType: "application/json", // IMPORTANTE: Indica que envías JSON
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'X-ID-CLIENT': '<?= ID_CLIENT ?>'
                        },
                        success: function(respuestaBackend) {
                            if(respuestaBackend.status === 'success') {
                                //alert("¡Pago Exitoso! ID de transacción: " + respuestaBackend.transaction_id);
                                // Opcional: Redirigir a página de éxito
                                //window.location.href =  respuestaBackend.url;
                                window.location.replace(respuestaBackend.url);
                            } else if (respuestaBackend.status === 'pending') {
                                // Manejo de 3D Secure (Si el banco pide autenticación extra)
                                window.location.href = respuestaBackend.url;
                            }
                        },
                        error: function(err) {
                            var errorMsg = err.responseJSON ? err.responseJSON.description : "Error interno en el servidor.";
                            //alert("Error en el cobro: " + errorMsg);

                            statusDiv.innerHTML = `
                                <div class="alert alert-danger d-flex align-items-center mt-3">
                                    <span class="me-2">❌</span>
                                    <div>Error en el cobro:  ${errorMsg}</div>
                                </div>`;                               

                            $btn.prop("disabled", false).text("Confirmar Pago");
                        }
                    });



                }, function(err) {
                    // --- CASO ERROR: Fallo al generar el token (ej. tarjeta inválida) ---
                    var desc = err.data.description != undefined ? err.data.description : err.message;
                    statusDiv.innerHTML = `
                        <div class="alert alert-danger d-flex align-items-center mt-3">
                            <span class="me-2">❌</span>
                            <div>Error con la tarjeta:  ${desc}</div>
                        </div>`;                    

                    $btn.prop("disabled", false).text("Confirmar Pago");
                });
            });




        });
    </script>
<?php }
    else{
        $api_url = URL_API."SQUARE";
        $data = '';
        $square_account = json_decode(API($jwt,$api_url,$data,'GET'), true);            
?>
    <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
    <script>
            const appId = '<?php echo $square_account['Id'];?>';
            const locId = '<?php echo $square_account['LocalId'];?>';

            async function initSquare() {
            const payments = Square.payments(appId, locId);
            const card     = await payments.card();
            await card.attach('#card-container');

            document.getElementById('card-button').addEventListener('click', async () => {
                try {
                    const result = await card.tokenize();
                    if (result.status === 'OK') {
                        await procesarPago(result.token);
                    }
                } catch (e) {
                    console.error('Error al tokenizar:', e);
                }
            });
        }
        
        async function procesarPago(token_square) {
            const statusDiv = document.getElementById('payment-status-container');
            const payButton = document.getElementById('card-button');
            $('#token_id').val(token_square);
            // 1. Bloquear botón y mostrar icono de carga
            payButton.disabled = true;
            payButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Procesando pago...
            `;
            
            statusDiv.innerHTML = ""; // Limpiar mensajes previos

            try {
                var datosFormulario = $('#payment-form').serialize();
                

                    $.ajax({
                        type: "POST",
                        url: url_api +'processpayment_square',
                    // IMPORTANTE: Convierte tu objeto a una cadena JSON
                        data:JSON.stringify(Object.fromEntries(new URLSearchParams(datosFormulario))),
                        dataType: "json",
                        contentType: "application/json", // IMPORTANTE: Indica que envías JSON
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'X-ID-CLIENT': '<?= ID_CLIENT ?>'
                        },                      
                        success: function(respuestaBackend) {
                            if(respuestaBackend.status === 'success') {
                                window.location.replace(respuestaBackend.url);
                            } else if (respuestaBackend.status === 'pending') {
                                //window.location.href = respuestaBackend.url;
                            }
                        },
                        error: function(err) {
                            var errorMsg = err.responseJSON ? err.responseJSON.description : "Error interno en el servidor.";

                            statusDiv.innerHTML = `
                                <div class="alert alert-danger d-flex align-items-center mt-3">
                                    <span class="me-2">❌</span>
                                    <div>${errorMsg}</div>
                                </div>`;                            

                            payButton.disabled = false;
                            payButton.innerHTML = "Reintentar Pago";
                        }
                    });                
            } catch (error) {
                statusDiv.innerHTML = `
                    <div class="alert alert-danger d-flex align-items-center mt-3">
                        <span class="me-2">❌</span>
                        <div>${error.message}</div>
                    </div>`;
                
                // Reactivar el botón para que el usuario pueda intentar de nuevo
                payButton.disabled = false;
                payButton.innerHTML = "Reintentar Pago";
            }
        }

        initSquare();
    </script>
<?php }?>



<?php require_once TEMPLATE.'social.php'; ?>
<?php require_once TEMPLATE.'cart.php'; ?>
<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>
<script src="<?php echo TEMPLATE;?>js/idx-template.js"></script>
<script src="js/general.js"></script>
<script>
    $(document).ready(function() {
    const url = '<?= $token ?>.pdf'; // Ruta de tu PDF
    const pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;

        if (pdfjsLib) {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

            pdfjsLib.getDocument(url).promise.then(pdf => {
                pdf.getPage(1).then(page => {
                    const canvas = document.getElementById('pdf-canvas');
                    const context = canvas.getContext('2d');

                    // Ajustamos la escala para que se vea bien en la columna pequeña
                    const viewport = page.getViewport({ scale: 0.5 });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    page.render(renderContext).promise.then(() => {
                        // Ocultar el spinner cuando termine de renderizar
                        $('#loader-pdf').fadeOut();
                    });
                });
            }).catch(err => {
                console.error("Error al cargar PDF:", err);
                $('#loader-pdf').html('<small class="text-danger">Error</small>');
            });
        }    

    });
</script>
</body>
</html>