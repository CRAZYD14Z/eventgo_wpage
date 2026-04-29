<?php 
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 
/*
    include_once 'database.php'; 
    $database = new Database();
    $db = $database->getConnection();        
*/    

?>
    <link rel="stylesheet" href="css/general.css">
    <style>

        #scroll-indicator {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: rgba(13, 110, 253, 0.9);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            pointer-events: none;
            transition: opacity 0.5s ease;
        }

        .contract-content {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            line-height: 1.8;
            font-size: 1.1rem;
        }

        .signature-area {
            border-top: 2px solid #eee;
            margin-top: 50px;
            padding-top: 30px;
        }

        .canvas-container {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            background: #fafafa;
            height: 250px;
            touch-action: none;
        }

        canvas { width: 100%; height: 100%; cursor: url('https://img.icons8.com/ios-filled/20/000000/edit--v1.png'), auto; }


/* Centra el cargador en la pantalla o contenedor */
.loader-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px; /* Ajusta según necesites */
    width: 100%;
}

/* El Spinner (Círculo giratorio) */
.loader {
    border: 10px solid #f3f3f3; /* Gris claro */
    border-top: 10px solid #3498db; /* Azul (cambia a tu color corporativo) */
    border-radius: 50%;
    width: 80px;
    height: 80px;
    animation: spin 1s linear infinite;
    margin-bottom: 15px;
}

/* Animación de giro */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}        


    </style>
</head>
<body>

<?php require_once TEMPLATE.'nav.php'; ?>


<div class="container py-5">

<?php
    if (!isset($_GET['Id'])){
        echo "Enlace no válido.";
        die();
    }

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

    if ($cotizacion['Contrato'] !=""){
            echo "El documento ya cuenta con contrato firmado.";
            die();        
    }
?>

<div id="scroll-indicator">
    <span>⬇ Deslice hacia abajo para leer y firmar</span>
</div>

<div id="main-container" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="contract-content">
                <h1 class="text-center mb-5">Contrato</h1>

                <div id="loader-container" class="loader-wrapper">
                    <div class="loader"></div>
                    <p>Generando contrato...</p>
                </div>

                <div id="Contract" style="display: none;">
                    <?php
                        $api_url = URL_API."document_center";
                        $data = json_encode(['Tipo' => 'contract','IdTemplate' => 2,'Idioma' => $lang,]);
                        $Template = json_decode(API($jwt,$api_url,$data,'GET'), true);
                        echo $Template['Template'];
                    ?>
                </div>

                <div class="signature-area" id="signature-section">
                    <h4 class="mb-4">Aceptación y Firma</h4>
                    <form id="signature-form">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Firma Digital</label>
                            <div class="canvas-container">
                                <canvas id="signature-pad"></canvas>
                            </div>
                            <div class="mt-2 d-flex justify-content-between">
                                <small class="text-muted">Use su ratón o dedo para firmar arriba</small>
                                <button type="button" id="clear-btn" class="btn btn-link btn-sm text-danger text-decoration-none">Borrar firma</button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Nombre Completo del Firmante</label>
                            <input type="text" id="signer-name" class="form-control form-control-lg" placeholder="Escriba su nombre completo" required>
                        </div>                        

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" id="CrearDocumento" class="btn btn-primary btn-lg">Validar y Enviar Contrato</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Inicializar Signature Pad
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        // 2. Control del Indicador de Scroll
        $(window).scroll(function() {
            // Si el usuario llega a la sección de firma (o cerca de ella), ocultar indicador
            const signatureOffset = $('#signature-section').offset().top - window.innerHeight;
            if ($(window).scrollTop() > signatureOffset) {
                $('#scroll-indicator').css('opacity', '0');
            } else {
                $('#scroll-indicator').css('opacity', '1');
            }
        });

        // 3. Botón Limpiar
        $('#clear-btn').click(function() {
            signaturePad.clear();
        });

        // 4. Envío de Formulario
        $('#signature-form').submit(function(e) {
            e.preventDefault();
            if (signaturePad.isEmpty()) {
                alert("Por favor, firme el documento antes de continuar.");
                return;
            }
            
            const data = {
                nombre: $('#signer-name').val(),
                firma: signaturePad.toDataURL()
            };
            
            console.log("Datos capturados:", data);
            //alert("¡Contrato firmado con éxito por " + data.nombre + "!");
            const dataURL = signaturePad.toDataURL();

            $('#img-firma-tabla').attr('src', dataURL).show();

            //const contenidoDiv = document.getElementById('Contract').innerHTML;
            //contenidoDiv = contenidoDiv.replace("*signeddate*", "<?php echo date('Y-m-d')?>"); 
            //contenidoDiv = contenidoDiv.replace("*customerdsname*", $('#signer-name').val()); 
            //$('#Contract').html(Contract);
            let contenido = $('#Contract').html(); 

            // 2. Ejecutamos los reemplazos (encadenados para mayor limpieza)
            contenido = contenido.replace("*signeddate*", "<?php echo date('Y-m-d')?>")
                                .replace("*customerdsname*", $('#signer-name').val()); 

            // 3. Volvemos a insertar el contenido procesado en el div
            $('#Contract').html(contenido);            

            const contenidoDiv = document.getElementById('Contract').innerHTML;            
            //alert(contenidoDiv)
            const datos = { 
                token: '<?php echo $token;?>',
                contrato: contenidoDiv 
            };

            $('#CrearDocumento')
                .prop('disabled', true)
                .html('<i class="fa-solid fa-spinner fa-spin"></i> Procesando...');            

            fetch('pdf.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {

                //$('#CrearDocumento')
                //.prop('disabled', false)
                //.html('Validar y Enviar Contrato');
                //console.log('Éxito:', data);
                //alert('Contrato guardado correctamente ' + data.UUID);
                var url = "makepayment.php?Id="+data.UUID;
                $(location).attr('href', url);
            })
            .catch((error) => {
                console.error('Error:', error);
            });            


        });

        LoadDocument();

        $("#loader-container").fadeOut(400, function() {
            // Esta función se ejecuta CUANDO termina el fadeOut
            $("#Contract").fadeIn(500); 
        });    
    });

function LoadDocument(){
    <?php
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

    ?>

const FHI = '<?php echo $lead['StartDateTime']?>';
const FHF = '<?php echo $lead['EndDateTime']?>';
const FHIp = FHI.split(' ')
const FHFp = FHF.split(' ')


    const datosGenerales = {
        leadid: "<?= $lead['Folio'] ?>",
        contractsentdate: "<?= date('Y-m-d') ?>",
        company_logo: "<?php echo $account['Logo']?>",
        company_name: "<?php echo $account['NombreCompania']?>",
        company_address:"<?php echo $account['Direccion']." ".$account['Direccion2'];?>",
        company_city:"<?php echo $account['Ciudad']." ".$account['CP'];?>",
        company_state:"<?php echo $account['Estado'];?>",
        company_phone:"<?php echo $account['TelefonoCelular']?>",

        organization:"<?php echo isset($organization['Nombre']) ? $organization['Nombre'] : '' ?>",
        ctfirstname:"<?php echo isset($customer['Nombres']) ? $customer['Nombres'] : '' ?>",
        ctlastname:"<?php echo isset($customer['Apellidos']) ? $customer['Apellidos'] : '' ?>",
        eventstreet:"<?php echo $venue['Direccion']?>",
        eventcity:"<?php echo $venue['Ciudad']?>",
        eventstate:"<?php echo $venue['Estado']?>",
        eventzip:"<?php echo $venue['CP']?>",
        phones:"<?php echo $venue['Telefono']?>",
        startdate: FHIp[0],
        starttime: FHIp[1],
        enddate: FHFp[0],
        endtime: FHFp[1],
        deliverytype:"ON SITE",

        itemtotals: "<?php echo $lead['ItemTotals']?>",
        distanecharges: "<?php echo $lead['DistanceCharges']?>",
        staffcosts: "<?php echo $lead['StafCost']?>",
        discount: "<?php echo $lead['Discount']?>",

        subtotal: "<?php echo $lead['SubTotal']?>",
        taxexcempt: "<?php echo $lead['TaxId']?>",
        taxrate: "<?php echo $lead['TaxPc']?>",
        salestax: "<?php echo $lead['TaxAmount']?>",
        tip: "<?php echo $lead['Tip']?>",
        total: "<?php echo $lead['Total']?>",
        apayment: "<?php echo $lead['DepositAmount']?>",
        ctr_balance_due: "<?php echo $lead['Balance']?>",

        electric:""
    };

    const productos = [];
    const descuentos = [];

<?php
    //echo $respuesta['script_push'];
    $push_procesado = str_replace('.avif', '.jpg', $respuesta['script_push']);
    echo $push_procesado;
    if ($discounts) {
        foreach ($discounts as $discount) {
            echo "
            let descuento".$discount['Id']." = {
                concepto: '".$discount['Name']."',
                monto:'".$discount['AmountVal']."'
            };
            descuentos.push(descuento".$discount['Id'].");";
        }
    }      
?>


    const $contenedor = $('#contrato-dsj');
    const $cuerpoTabla = $('#lista-productos');
    const $filaPlantilla = $cuerpoTabla.find('.item-fila').first();
    ejecutarRenderizadoContract($contenedor, $cuerpoTabla, $filaPlantilla, datosGenerales, productos,descuentos);

    <?php 
        if ($lead['Tip'] > 0){
            echo"
                $('#tips').show();
            ";
        }
        else{
            echo"
                $('#tips').hide();
            ";
        }
    ?>    

}    

function ejecutarRenderizadoContract($contenedor, $cuerpoTabla, $filaPlantilla, datosGenerales, productos,descuentos) {
    // 1. Limpiar productos previos (excepto la plantilla)
    $cuerpoTabla.find('tr:not(.item-fila)').remove();

    // 2. Procesar y agregar cada producto
    productos.forEach(producto => {
        let nuevaFilaHtml = $filaPlantilla[0].outerHTML;
        $.each(producto, function(key, val) {
            let regex = new RegExp('\\*' + key + '\\*', 'g');
            nuevaFilaHtml = nuevaFilaHtml.replace(regex, val ?? '');
        });
        
        let $nuevaFila = $(nuevaFilaHtml).removeClass('item-fila').css('display', ''); // Quitar display:none
        $cuerpoTabla.append($nuevaFila);
    });


    const $contenedorDescuentos = $contenedor.find('#extra_discounts');
    const $filaOriginal = $contenedorDescuentos.find('tr').first();

    if ($filaOriginal.length > 0) {
        const htmlPlantillaDesc = $filaOriginal[0].outerHTML;
        $contenedorDescuentos.empty(); // Limpiamos después de copiar la plantilla

        descuentos.forEach(desc => {
            let filaDescHtml = htmlPlantillaDesc
                .replace('*conceptdiscount*', desc.concepto)
                .replace('*discountconcept*', desc.monto);
            $contenedorDescuentos.append(filaDescHtml);
        });
    }    


    // 3. Lógica para ocultar IDs si el valor es 0
    $.each(datosGenerales, function(key, val) {
        // Buscamos el elemento que tenga el ID igual a la 'key'
        let $elemento = $contenedor.find('#' + key);
        
        if (val === 0 || val === "0") {
            $elemento.hide(); // Oculta el elemento si es cero
        } else {
            $elemento.show(); // Se asegura de mostrarlo si tiene valor
        }

        // 4. Reemplazar etiquetas en el HTML (Mantenemos tu lógica de reemplazo)
        let regex = new RegExp('\\*' + key + '\\*', 'g');
        let contenidoActual = $contenedor.html();
        $contenedor.html(contenidoActual.replace(regex, val ?? ''));
    });

}



</script>


</div>
<?php require_once TEMPLATE.'social.php'; ?>
<?php require_once TEMPLATE.'cart.php'; ?>
<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>
<script src="<?php echo TEMPLATE;?>js/idx-template.js"></script>
<script src="js/general.js"></script>
<script>
</script>
</body>
</html>