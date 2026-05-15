<?php 
    ob_start();
    session_start();
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 

    //include_once 'database.php'; 
    //$database = new Database();
    //$db = $database->getConnection();    

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
<?php 
    require_once TEMPLATE.'nav.php'; 

    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "quote"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);    
    ?>
<div class="container py-5">



<?php
    if (!isset($_GET['Id'])){
        echo Trd(1);
        die();
    }

    $Tip=0;
    if (isset($_GET['Tip'])){
        $Tip=$_GET['Tip'];
    }

    $APay=20;
    if (isset($_GET['APay'])){
        $APay=$_GET['APay'];
    }    

    if ($APay != 20 AND $APay != 50 AND $APay != 100){
        echo Trd(2)."  $APay ".Trd(3);
        die();        
    }

    $PQ=0;
    if (isset($_GET['PQ'])){
        $PQ=$_GET['PQ'];
    }       

    $token = $_GET['Id']; // El UUID de la URL
    $ahora = date("Y-m-d H:i:s");
    

    $api_url = URL_API."quotes";
    $data = json_encode(['token' => $token]);
    $cotizacion = json_decode(API($jwt,$api_url,$data,'GET'), true);

    if ($cotizacion) {
        // Verificar si la fecha actual es mayor a la de expiración
        if ($ahora > $cotizacion['ExpDate']) {
            echo Trd(4)." " . $cotizacion['ExpDate']." $ahora";
            die();
        }
    } else {
        echo Trd(1);
        die();
    }
    
    if ($cotizacion['Contrato'] !=""){
            echo Trd(5);
            die();        
    }


?>

     <?php

        $api_url = URL_API."quote_account";
        //$data = json_encode(['token' => $token]);
        $data ='';
        $account = json_decode(API($jwt,$api_url,$data,'GET'), true);

        $api_url = URL_API."tip_deposit";
        $data = json_encode(['tip' => $Tip,'apay' => $APay, 'pq' => $PQ ,'quote' => $cotizacion['IdQuote']]);
        $Tips = json_decode(API($jwt,$api_url,$data,'POST'), true);
        
        $api_url = URL_API."quote_data";
        $data = json_encode(['lead' => $cotizacion['IdQuote']]);        
        $respuesta = json_decode(API($jwt,$api_url,$data,'GET'), true);

        $lead = $respuesta['lead'];
        $lead_details =  $respuesta['lead_details'];
        $customer = $respuesta['customer'];        
        $organization =  $respuesta['organization'];
        $venue =  $respuesta['venue'];
        $discounts =  $respuesta['discounts'];
        $gifcrds =  $respuesta['gifcrds'];

    ?>

<div id="scroll-indicator">
    <span>⬇ <?= Trd(6) ?> ⬇</span>
</div>

<div id="main-container" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="contract-content">
                <h1 class="text-center mb-5"><?= Trd(7) ?></h1>
                
                <div id="loader-container" class="loader-wrapper">
                    <div class="loader"></div>
                    <p><?= Trd(8) ?></p>
                </div>

                <div id="Quote" style="display: none;">
                    <?php
                        $api_url = URL_API."document_center";
                        $data = json_encode(['Tipo' => 'quote','IdTemplate' => 4,'Idioma' => $lang,]);
                        $Template = json_decode(API($jwt,$api_url,$data,'GET'), true);
                        echo $Template['Template'];
                    ?>
                </div>

                

                <div class="signature-area" id="signature-section">

                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-confirmacion-tip" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); font-family: 'Segoe UI', Arial, sans-serif;">
    <div style="background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <div style="font-size: 18px; font-weight: bold; color: #002d72; margin-bottom: 15px;"><?= Trd(9) ?></div>
        <p style="font-size: 14px; color: #555; margin-bottom: 25px;">
            ¿<?= Trd(10) ?> <span id="text-monto-confirmar" style="font-weight: bold; color: #27ae60;">$0.00</span> <?= Trd(11) ?>?
        </p>
        <div style="display: flex; justify-content: space-around;">
            <button id="btn-cancelar-tip" style="padding: 10px 20px; border: 1px solid #ccc; background: #fff; border-radius: 4px; cursor: pointer; color: #666;"><?= Trd(12) ?></button>
            <button id="btn-aceptar-tip" style="padding: 10px 20px; border: none; background: #27ae60; color: #fff; border-radius: 4px; cursor: pointer; font-weight: bold;"><?= Trd(13) ?></button>
        </div>
    </div>
</div>

<div id="modal-confirmacion-PQE" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); font-family: 'Segoe UI', Arial, sans-serif;">
    <div style="background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <div style="font-size: 18px; font-weight: bold; color: #002d72; margin-bottom: 15px;"><?= Trd(14) ?></div>
        <p style="font-size: 14px; color: #555; margin-bottom: 25px;">
            <?= Trd(15) ?> <span style="font-weight: bold; color: #27ae60;">$<?=  $account['DepositAmount'] ?></span> 
        </p>
        <div style="display: flex; justify-content: space-around;">
            <button id="btn-aceptar-pqe" style="padding: 10px 20px; border: none; background: #27ae60; color: #fff; border-radius: 4px; cursor: pointer; font-weight: bold;"><?= Trd(16) ?></button>
        </div>
    </div>
</div>

<div id="modal-confirmacion-PQEE" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); font-family: 'Segoe UI', Arial, sans-serif;">
    <div style="background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <div style="font-size: 18px; font-weight: bold; color: #002d72; margin-bottom: 15px;"><?= Trd(14) ?></div>
        <p style="font-size: 14px; color: #555; margin-bottom: 25px;">
            <?= Trd(17) ?> <span style="font-weight: bold; color: #27ae60;">$<?=  $lead['Total'] ?></span> 
        </p>
        <div style="display: flex; justify-content: space-around;">
            <button id="btn-aceptar-pqee" style="padding: 10px 20px; border: none; background: #27ae60; color: #fff; border-radius: 4px; cursor: pointer; font-weight: bold;"><?= Trd(16) ?></button>
        </div>
    </div>
</div>

<div id="modal-confirmacion-PQ" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); font-family: 'Segoe UI', Arial, sans-serif;">
    <div style="background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <div style="font-size: 18px; font-weight: bold; color: #002d72; margin-bottom: 15px;"><?= Trd(18) ?></div>
        <p style="font-size: 14px; color: #555; margin-bottom: 25px;">
            ¿<?= Trd(19) ?><span id="text-monto-confirmarPQ" style="font-weight: bold; color: #27ae60;">$0.00</span> ?
        </p>
        <div style="display: flex; justify-content: space-around;">
            <button id="btn-cancelar-PQ" style="padding: 10px 20px; border: 1px solid #ccc; background: #fff; border-radius: 4px; cursor: pointer; color: #666;"><?= Trd(20) ?></button>
            <button id="btn-aceptar-pq" style="padding: 10px 20px; border: none; background: #27ae60; color: #fff; border-radius: 4px; cursor: pointer; font-weight: bold;"><?= Trd(21) ?></button>
        </div>
    </div>
</div>


<div id="modal-remove-tip" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); font-family: 'Segoe UI', Arial, sans-serif;">
    <div style="background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <div style="font-size: 18px; font-weight: bold; color: #002d72; margin-bottom: 15px;"><?= Trd(22) ?></div>
        <p style="font-size: 14px; color: #555; margin-bottom: 25px;">
            ¿<?= Trd(23) ?>?
        </p>
        <div style="display: flex; justify-content: space-around;">
            <button id="btn-cancelar-remove-tip" style="padding: 10px 20px; border: 1px solid #ccc; background: #fff; border-radius: 4px; cursor: pointer; color: #666;"><?= Trd(24) ?></button>
            <button id="btn-aceptar-remove-tip" style="padding: 10px 20px; border: none; background: #27ae60; color: #fff; border-radius: 4px; cursor: pointer; font-weight: bold;"><?= Trd(25) ?></button>
        </div>
    </div>
</div>


<div id="modal-change-apay" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); font-family: 'Segoe UI', Arial, sans-serif;">
    <div style="background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <div style="font-size: 18px; font-weight: bold; color: #002d72; margin-bottom: 15px;"><?= Trd(26) ?></div>
        <p style="font-size: 14px; color: #555; margin-bottom: 25px;">
            ¿<?= Trd(27) ?> <span id="text-monto-anticipo" style="font-weight: bold; color: #27ae60;">$0.00</span>?
        </p>
        <div style="display: flex; justify-content: space-around;">
            <button id="btn-cancelar-apay" style="padding: 10px 20px; border: 1px solid #ccc; background: #fff; border-radius: 4px; cursor: pointer; color: #666;"><?= Trd(28) ?></button>
            <button id="btn-aceptar-apay" style="padding: 10px 20px; border: none; background: #27ae60; color: #fff; border-radius: 4px; cursor: pointer; font-weight: bold;"><?= Trd(29) ?></button>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>



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
        totalbt: "<?php echo $lead['TotalBT']?>",
        apayment: "<?php echo $lead['DepositAmount']?>",
        <?php  if ($account['Deposit'] == 1){?>
            balancedue: "<?php if ($lead['BalanceQ'] == ""){echo $lead['Total'] - $lead['DepositAmount'];}else{echo $lead['BalanceQ'];}?>",
        <?php  } else {?>
            balancedue: "<?php echo $lead['Total'];?>",        
        <?php  }?>
        electric:"",
        signature:"",
        signeddate:"",
        "link_to_accept":'contract.php?Id=<?php echo $token;?>'
    };

    const productos = [];
    const descuentos = [];




<?php
    echo $respuesta['script_push'];

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
    if ($gifcrds) {
        foreach ($gifcrds as $discount) {
            echo "
            let descuento".$discount['Id']." = {
                concepto: '".$discount['Descript']."',
                monto:'".$discount['AmountVal']."'
            };
            descuentos.push(descuento".$discount['Id'].");";
        }
    }   
    

?>

    $(document).ready(function() {
        // 1. Inicializar Signature Pad

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

        LoadDocument();

        let montoPendiente = 0;
        let anticipo = 0;
        let pq = 0;


        // 1. Evento para los botones de %
        $(document).on('click', '.btn-tip', function() {
            
            const porcentaje = $(this).data('tip');
            const subtotal = parseFloat(datosGenerales.totalbt) || 0; // Ajusta según tu variable de precio
            montoPendiente = (subtotal * (porcentaje / 100)).toFixed(2);
            
            abrirModalConfirmacion(montoPendiente);
        });

        // 2. Evento para el input manual (cuando pierde el foco y tiene valor)
        $(document).on('change', '#custom-tip', function() {
            const valor = parseFloat($(this).val());
            if (valor > 0) {
                montoPendiente = valor.toFixed(2);
                abrirModalConfirmacion(montoPendiente);
            }
        });

        $(document).on('change', '#custom-pay-qnt', function() {
            const valor = parseFloat($(this).val());
            if (valor > 0) {
                montoPendiente = valor.toFixed(2);
                abrirModalConfirmacionPQ(montoPendiente);
            }
        });



        // Funciones del Modal
        function abrirModalConfirmacion(monto) {
            $('#text-monto-confirmar').text('$' + monto);
            $('#modal-confirmacion-tip').fadeIn(200);
        }

        function abrirModalConfirmacionPQ(monto) {
            if (monto < <?=  $account['DepositAmount'] ?>){
                $('#modal-confirmacion-PQE').fadeIn(200);            
            }
            else if (monto > <?=  $lead['Total']?>){
                $('#modal-confirmacion-PQEE').fadeIn(200);            
            }                        
            else{
                $('#text-monto-confirmarPQ').text('$' + monto);
                $('#modal-confirmacion-PQ').fadeIn(200);
                pq = monto;
            }
        

        }        

        $(document).on('click', '#btn-cancelar-PQ', function() {
            $('#modal-confirmacion-PQ').fadeOut(200);
            $('#custom-pay-qnt').val(''); // Limpia el input si cancela
            montoPendiente = 0;
        });        

        $(document).on('click', '#btn-cancelar-tip', function() {
            $('#modal-confirmacion-tip').fadeOut(200);
            $('#custom-tip').val(''); // Limpia el input si cancela
            montoPendiente = 0;
        });

        $(document).on('click', '#btn-aceptar-tip', function() {
            $('#modal-confirmacion-tip').fadeOut(200);
            actualizarUrlYRedirigir({ Tip: montoPendiente });
        });

        $(document).on('click', '.btn-tip-remove', function() {
            $('#modal-remove-tip').fadeIn(200);
        });        

        $(document).on('click', '#btn-cancelar-remove-tip', function() {
            $('#modal-remove-tip').fadeOut(200);
        });

        $(document).on('click', '#btn-aceptar-remove-tip', function() {
            $('#modal-remove-tip').fadeOut(200);
            actualizarUrlYRedirigir({ Tip: 0 });
        });




        $(document).on('click', '.btn-apay', function() {
            const porcentaje = $(this).data('apay');
            const subtotal = parseFloat(datosGenerales.total) || 0; // Ajusta según tu variable de precio
            anticipo = porcentaje;
            //alert (anticipo)
            abrirModalAnticipo((subtotal * (porcentaje / 100)).toFixed(2));
        });

        $(document).on('click', '.btn-qnt', function() {
            pq = $(this).data('apay');
            //const subtotal = parseFloat(datosGenerales.total) || 0; // Ajusta según tu variable de precio
            //anticipo = porcentaje;
            //alert (anticipo)
            abrirModalConfirmacionPQ(pq);
        });



        function abrirModalAnticipo(monto) {
            $('#text-monto-anticipo').text('$' + monto);
            $('#modal-change-apay').fadeIn(200);
        }        

        $(document).on('click', '#btn-cancelar-apay', function() {
            $('#modal-change-apay').fadeOut(200);
        });        


        $(document).on('click', '#btn-aceptar-apay', function() {
            $('#modal-confirmacion-tip').fadeOut(200);
            actualizarUrlYRedirigir({ APay: anticipo });
        });

        $(document).on('click', '#btn-aceptar-pq', function() {
            $('#modal-confirmacion-PQ').fadeOut(200);
            //alert(pq)
            actualizarUrlYRedirigir({ PQ: pq });
        });        


        $(document).on('click', '#btn-aceptar-pqe', function() {
            $('#modal-confirmacion-PQE').fadeOut(200);
        });
        $(document).on('click', '#btn-aceptar-pqee', function() {
            $('#modal-confirmacion-PQEE').fadeOut(200);
        });

        function actualizarUrlYRedirigir(nuevosParams) {

            // 1. Capturamos los parámetros actuales de la URL
            let params = new URLSearchParams(window.location.search);

            // 2. Iteramos sobre los nuevos parámetros y los aplicamos
            // Esto sobreescribe si ya existen o los crea si no.
            Object.keys(nuevosParams).forEach(key => {
                params.set(key, nuevosParams[key]);
            });

            // 3. Construimos la nueva URL de destino
            // window.location.pathname contiene la ruta sin los parámetros viejos
            let nuevaUrl = window.location.origin + window.location.pathname + '?' + params.toString();

            // 4. Redirigimos
            window.location.href = nuevaUrl;
        }        


        <?php 
            if ($lead['Tip'] > 0){
                echo"
                    $('#tip-add').hide();
                    $('#tip-remove').show();
                    $('#tips').show();
                ";
            }
            else{
                echo"
                    $('#tip-add').show();
                    $('#tip-remove').hide();
                    $('#tips').hide();
                ";
            }

            if ($lead['Deposit'] == 20){
                echo "$('#apay-20').prop('disabled', true);
                      $('#apay-20').css('background-color', '#ccc');
                      $('#apay-20').css('cursor', 'default');
                ";
            }
            elseif ($lead['Deposit'] == 50){
                echo "$('#apay-50').prop('disabled', true);
                      $('#apay-50').css('background-color', '#ccc');
                      $('#apay-50').css('cursor', 'default');                
                ";
            }
            elseif($lead['Deposit'] == 100){
                echo "$('#apay-100').prop('disabled', true);
                      $('#apay-100').css('background-color', '#ccc');
                      $('#apay-100').css('cursor', 'default');";
            }

        ?>

    });


    function LoadDocument(){

        const $contenedor = $('#cotizacion-dsj');
        const $cuerpoTabla = $('#lista-productos');
        const $filaPlantilla = $cuerpoTabla.find('.item-fila').first();
        ejecutarRenderizadoQuote($contenedor, $cuerpoTabla, $filaPlantilla, datosGenerales, productos,descuentos);

        $("#loader-container").fadeOut(400, function() {
            // Esta función se ejecuta CUANDO termina el fadeOut
            $("#Quote").fadeIn(500); 
        });

    }    

    function ejecutarRenderizadoQuote($contenedor, $cuerpoTabla, $filaPlantilla, datosGenerales, productos,descuentos) {
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

                let gfcrd  ='';
                const codigo = desc.concepto;
                const regex = /^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/;
                if (regex.test(codigo)) {
                    gfcrd = '<?= Trd(30) ?><br>'
                }     

                let filaDescHtml = htmlPlantillaDesc
                    .replace('*conceptdiscount*', gfcrd+desc.concepto)
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

        <?php
        if ($account['Deposit'] == 1){
            if ($account['DepositType'] == 'percentage'){
                echo "$('#tip-adv-pay-qnt').hide();";
            }
            else{
                if (($account['DepositAmount'] * 1) == ($PQ * 1) OR $PQ == 0 )
                    echo "$('#custom-pay-qnt').val('');";
                else
                    echo "$('#custom-pay-qnt').val($PQ);";

                echo "$('#tip-adv-pay-pct').hide();";
                echo "$('#pay-qnt').html('$'+'". number_format( $account['DepositAmount'],2,'.',',') ."');";
                echo "$('#pay-qnt').attr('data-apay', '".$account['DepositAmount']."');";
                
            }
        }else{
            
            echo "$('#tr-apay').hide();";
            echo "$('#tip-adv-pay-qnt').hide();";
            echo "$('#tip-adv-pay-pct').hide();";
        }

        ?>

    }
</script>



</div>
<?php require_once TEMPLATE.'social.php'; ?>
<?php require_once TEMPLATE.'cart.php'; ?>
<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>
<script src="<?php echo TEMPLATE;?>js/idx-template.js"></script>
<script src="js/general.php"></script>
<script>
</script>
</body>
</html>