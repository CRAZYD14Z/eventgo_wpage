<?php 
    ob_start();
    session_start();
    require 'vendor/autoload.php';
    require_once 'config.php';
    require_once 'functions.php';
    require_once TEMPLATE.'head.php'; 

?>
    <link rel="stylesheet" href="<?php echo URL_BASE."/";?>css/general.css">
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff8f8;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }        
    </style>
</head>
<body>

<?php require_once TEMPLATE.'nav.php'; ?>

<div class="container py-5">
<?php
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "checkout"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);    
    require_once TEMPLATE.'checkout.php';
    $Traducciones_Rsp = $Traducciones;
?>
</div>

<?php require_once TEMPLATE.'social.php'; ?>

<?php require_once TEMPLATE.'cart.php'; ?>

<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>
<script src="<?php echo URL_BASE."/".TEMPLATE;?>js/idx-template.js"></script>
<script src="<?php echo URL_BASE."/";?>js/general.php"></script>
<?php 
    $Traducciones = $Traducciones_Rsp;
?>
<script>

function cargarResumenCheckout() {
    const data = obtenerDatosRaw(); // La función que blindamos antes
    const $contenedor = $('#checkout_items');
    
    // 1. Llenar Cabecera
    if (data.cabecera) {
        //alert(data.cabecera.fecha)
        $('#resumen_fecha').text(data.cabecera.fecha || '<?= Trd(1) ?>');
        $('#resumen_hInicio').text(data.cabecera.hInicio || '--:--');
        $('#resumen_hFin').text(data.cabecera.hFin || '--:--');
    }

    // 2. Llenar Items y Subitems
    $contenedor.empty();
    if (data.items.length === 0) {
        $contenedor.html('<p class="text-center py-4 text-muted"><?= Trd(2) ?></p>');
    }

data.items.forEach(item => {
        // 1. Generar HTML de extras ya agregados
        let htmlExtrasAgregados = '';
        if (item.adicionales && item.adicionales.length > 0) {
            item.adicionales.forEach(extra => {
                htmlExtrasAgregados += `
                    <div class="d-flex justify-content-between align-items-center mb-1 text-muted" style="font-size: 0.75rem;">
                        <span><i class="fa-solid fa-check text-success me-1"></i> ${extra.nombre}</span>
                        <div>
                            <span class="me-2">+$${extra.precio}</span>
                            <button class="btn btn-link btn-sm p-0 text-danger" onclick="eliminarAdicionalDeItem('${item.id}', '${extra.id}');cargarResumenCheckout()">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>`;
            });
        }

        // 2. HTML del ítem principal
        const itemHtml = `
            <div class="item-checkout mb-4 border-bottom pb-3">
                <div class="d-flex align-items-start">
                    <img src="${item.imagen || 'https://via.placeholder.com/80'}" 
                        class="rounded-3 border me-3" 
                        style="width: 70px; height: 70px; object-fit: cover;">
                    
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold text-dark">${item.cantidad}x ${item.nombre}</span>
                            <span class="fw-bold">$${(item.precio * item.cantidad)}</span>
                        </div>

                        <div class="mt-2 border-top pt-2">
                            ${htmlExtrasAgregados}
                        </div>

                        <button class="btn btn-link p-0 text-primary small fw-bold text-decoration-none" 
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapse_${item.id}" onclick="cargar_accesorios(${item.id})">
                            <i class="fa-solid fa-circle-plus me-1"></i> <?= Trd(3) ?>
                        </button>

                        <div class="collapse" id="collapse_${item.id}">

                        </div>
                    </div>
                </div>
            </div>`;
        $contenedor.append(itemHtml);
    });

    // Actualizar totales en la vista
    

    const totales = obtenerTotales();
    $('#checkout_subtotal').text(`$${totales.total.toFixed(2)}`);

    let Descuento = 0;
    
if (data.cupon.code){
    if (data.cupon.code != ""){
        $('#chEtiquetaCupon').html(` <?= Trd(4) ?> ( ${data.cupon.code} )`);
        if (data.cupon.type == 'percentage'){
            Descuento = totales.total *  (data.cupon.val / 100);
        }
        else{
            Descuento =  data.cupon.val;
        }
        
    }
    else{
        $('#chEtiquetaCupon').html(` <?= Trd(5) ?>`);
    }
}
    $('#chDescuento').html(`$${formatCurrency(Descuento)}`);

    if (Descuento < totales.total)
        totales.total = totales.total - Descuento;    


    $('#checkout_total').text(`$${totales.total.toFixed(2)}`);
}

$(document).ready(function() {
    cargarResumenCheckout();
});

function agregarExtraRapido(itemId, itemRl,Name,Price,Image,Url) {
    agregarExtraAItem(itemId, itemRl,Name,Price,Image,Url)
    renderizarCarrito();
    cargarResumenCheckout(); // Refresca el resumen para mostrar el cambio
}

function cargar_accesorios(itemId){

    const $listaContenedor = $(`#collapse_${itemId}`);
    const token = "<?php echo $jwt;?>"; // Aquí va tu variable del token

    $listaContenedor.html('<div class="text-center py-2"><i class="fa-solid fa-spinner fa-spin"></i> <?= Trd(6) ?></div>');

    $.ajax({
        url: '<?php echo URL_API?>get_accesories/', // Tu endpoint de API
        type: 'GET',
        data: { producto_id: itemId },
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-ID-CLIENT': '<?= ID_CLIENT ?>',
            'LNG':'<?= $_SESSION['Idioma'] ?>'
        },
        success: function(response) {
            // Asumiendo que la API devuelve un array de objetos
            if (response.status === 'success' && response.Accesories.length > 0) {
                let html = '<div class="d-grid gap-2 mb-3">';
                
                response.Accesories.forEach(acc => {
                    html += `
                        <button class="btn btn-sm btn-outline-dark text-start py-2 d-flex align-items-center" 
                                onclick="agregarExtraRapido(${itemId}, ${acc.Producto_r}, '${acc.Name}', ${acc.Price}, '${acc.Image}','')">
                            <img src="<?php echo URL_IMAGES?>${acc.Image || ''}" class="rounded me-2" style="width:30px; height:30px; object-fit:cover;">
                            <div class="flex-grow-1">
                                <div class="d-block fw-bold" style="font-size:0.7rem;">${acc.Name}</div>
                                <span class="small">+$${acc.Price}</span>
                            </div>
                            <i class="fa-solid fa-plus-circle ms-2 opacity-50"></i>
                        </button>`;
                });
                
                html += '</div>';
                $listaContenedor.html(html);
                //$acordeon.data('cargado', true); // Marcar como cargado
            } else {
                $listaContenedor.html('<p class="small text-muted text-center"><?= Trd(7) ?></p>');
            }
        },
        error: function(xhr) {
            console.error("Error API:", xhr.status);
            $listaContenedor.html('<p class="small text-danger text-center"><?= Trd(8) ?></p>');
        }
    });    
}

$('#btn-validar').on('click', function() {
    const tel = $('#tel_cliente').val().trim();
    const email = $('#email_cliente').val().trim();
    const code = $('#gifcardcode').val().trim();

    // Caso: Error de campos vacíos (Warning)
    if (tel === '' && email === '') {
        lanzarAlerta('<?= Trd(9) ?>', 'warning');
        return;
    }

    // Caso: Error de formato (Error)
    const regexGifCard = /^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/i;
    if (!regexGifCard.test(code)) {
        lanzarAlerta('<?= Trd(10) ?>', 'error');
        return;
    }


    const paqueteFinal = {
        tel: tel,
        email: email,
        code: code
    };    


    $.ajax({
        url: '<?php echo URL_API?>validate_gifcard',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(paqueteFinal),
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-ID-CLIENT': '<?= ID_CLIENT ?>',
            'LNG':'<?= $_SESSION['Idioma'] ?>'
        },        
        beforeSend: function() {
            $('#btn-validar').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> <?= Trd(11) ?>');
        },
        success: function(response) {
            if (response.status === 'success') {
                // Limpiar carrito tras éxito
                lanzarAlerta(response.message, 'success');
                $('#btn-validar').prop('disabled', false).text('Validar');
                $('#gifcardcode').prop('readonly', true);
                let Amount = response.data.Amount * 1;
                $('#chTarjetaRegalo').html( `$${Amount.toFixed(2)}` )
                
                $('#Id').val(response.customer.Id)
                $('#Type').val(response.tipo)
                $('#Agfc').val(response.data.Amount)
                $('#Idgfc').val(response.data.Id)
                

                let Total = $('#checkout_total').text();
                
                Total = Total.replace("$", "");

                Total = (Total * 1 ) - (Amount * 1);
                $('#checkout_total').text(`$${Total.toFixed(2)}`)

                if(response.tipo=='C') {
                    $('#nombre_cliente').val(response.customer.Nombres);
                    $('#apellidos').val(response.customer.Apellidos);
                }
                else{
                    $('#organizacion').val(response.customer.Nombre);
                }

                
                $('#tel_cliente').val(response.customer.TelefonoCelular);
                $('#email_cliente').val(response.customer.Correo);
                $('#dir_cliente').val(response.customer.Direccion);
                $('#colonia_cliente').val(response.customer.Direccion2);
                $('#ciudad_cliente').val(response.customer.Ciudad);
                $('#colonia_cliente').val();
                $('#cp_cliente').val(response.customer.CP);

                

            } else {
                lanzarAlerta(response.message, 'error');
                $('#btn-validar').prop('disabled', false).text('Validar');
            }
        },
        error: function() {
            lanzarAlerta("<?= Trd(12) ?>.", 'error');
            $('#btn-validar').prop('disabled', false).text('Validar');
        }
    });    


    // Caso: Todo bien (Success)
    //lanzarAlerta('Código validado correctamente.', 'success');
    // 4. Si todo es correcto, procedemos a la API
    /*
    enviarConsultaAPI({
        nombre,
        apellidos,
        organizacion,
        code
    });
    */
});


$('#btn_copiar_direccion').on('click', function() {
    $('#dir_evento').val($('#dir_cliente').val())
    $('#ciudad_evento').val($('#ciudad_cliente').val())
    $('#colonia_evento').val($('#colonia_cliente').val())
    $('#cp_evento').val($('#cp_cliente').val())
});

$('#btn_enviar_cotizacion').on('click', function() {
  // Limpiar mensajes de error previos
    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');
    
    let isValid = true;
    let primerError = null;
    
    // Validar campos requeridos
    let camposRequeridos = [

        { selector: '#nombre_cliente', nombre: 'Nombre cliente' },
        { selector: '#apellidos', nombre: 'Apellidos' },
        { selector: '#dir_cliente', nombre: 'Dirección Completa' },
        { selector: '#ciudad_cliente', nombre: 'Código Postal' },
        { selector: '#colonia_cliente', nombre: 'Colonia' },
        { selector: '#cp_cliente', nombre: 'Código Postal' },    
        { selector: '#tel_cliente', nombre: 'Teléfono' },    
        { selector: '#email_cliente', nombre: 'Correo Electrónico' },    

        { selector: '#dir_evento', nombre: 'Dirección Completa' },
        { selector: '#ciudad_evento', nombre: 'Ciudad' },
        { selector: '#colonia_evento', nombre: 'Colonia' },
        { selector: '#cp_evento', nombre: 'Código Postal' },
        { selector: '#superficie', nombre: 'Superficie' },
        { selector: '#tipo_entrega', nombre: 'Tipo de Entrega' }
    ];
    
    camposRequeridos.forEach(campo => {
        let $campo = $(campo.selector);
        let valor = $campo.val().trim();
        
        if (valor === '') {
            isValid = false;
            $campo.addClass('is-invalid');
            
            // Agregar mensaje de error
            $campo.after(`<div class="invalid-feedback">${campo.nombre} <?= Trd(13) ?></div>`);
            
            if (!primerError) {
                primerError = $campo;
            }
        }
    });
    
// Validación más completa para correo electrónico
let email = $('#email_cliente').val().trim(); // Ajusta el ID según tu campo
if (email !== '') {
    // Regex más completo para email
    let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    if (!emailRegex.test(email)) {
        isValid = false;
        $('#email_cliente').addClass('is-invalid');
        if ($('#email_cliente').next('.invalid-feedback').length === 0) {
            $('#email_cliente').after('<div class="invalid-feedback"><?= Trd(14) ?> (ej: usuario@dominio.com)</div>');
        }
    }
    
    // Validación adicional para dominios comunes
    let dominio = email.split('@')[1];
    if (dominio && !dominio.includes('.')) {
        isValid = false;
        $('#email_cliente').addClass('is-invalid');
        if ($('#email_cliente').next('.invalid-feedback').length === 0) {
            $('#email_cliente').after('<div class="invalid-feedback"><?= Trd(15) ?></div>');
        }
    }
}    

    // Validaciones específicas para CP (solo números)
    let cp = $('#cp_cliente').val().trim();
    if (cp !== '' && !/^\d+$/.test(cp)) {
        isValid = false;
        $('#cp_cliente').addClass('is-invalid');
        if ($('#cp_cliente').next('.invalid-feedback').length === 0) {
            $('#cp_cliente').after('<div class="invalid-feedback"><?= Trd(16) ?></div>');
        }
    }

    let cp2 = $('#cp_evento').val().trim();
    if (cp2 !== '' && !/^\d+$/.test(cp)) {
        isValid = false;
        $('#cp_evento').addClass('is-invalid');
        if ($('#cp_evento').next('.invalid-feedback').length === 0) {
            $('#cp_evento').after('<div class="invalid-feedback"><?= Trd(17) ?></div>');
        }
    }

    
    if (!isValid) {
        // Hacer scroll al primer error
        if (primerError) {
            $('html, body').animate({
                scrollTop: primerError.offset().top - 100
            }, 500);
            primerError.focus();
        }
        return false;
    }

    const token = "<?php echo $jwt;?>"; // Aquí va tu variable del token
    // 1. Recolectar Datos de Contacto
    const contacto = {
        Id: $('#Id').val().trim(),
        Type: $('#Type').val().trim(),
        nombre: $('#nombre_cliente').val().trim(),
        apellidos: $('#apellidos').val().trim(),
        organizacion: $('#organizacion').val().trim(),
        telefono: $('#tel_cliente').val().trim(),
        correo: $('#email_cliente').val().trim(),        
        direccion: $('#dir_cliente').val().trim(),
        ciudad: $('#ciudad_cliente').val().trim(),
        colonia: $('#colonia_cliente').val().trim(),
        cp: $('#cp_cliente').val().trim(),
        estado:''
    };

    // 2. Recolectar Datos de Ubicación
    const ubicacion = {
        direccion: $('#dir_evento').val().trim(),
        ciudad: $('#ciudad_evento').val().trim(),
        colonia: $('#colonia_evento').val().trim(),
        cp: $('#cp_evento').val().trim(),
        estado:'',
        referencias: $('#ref_evento').val().trim(),
        superficie: $('#superficie').val().trim(),
        tipo_entrega: $('#tipo_entrega').val().trim(),
        cupon: $('#cupon').val().trim()
    };
    //tax: $('#tax').val().trim(),
    const cupon = {
        cupon: $('#CUPON').val().trim(),
        tipocupon: $('#TIPOCUPON').val().trim(),
        descuento: $('#DESCUENTO').val().trim(),
        idgfc: $('#Idgfc').val().trim(),
        gfc: $('#gifcardcode').val().trim(),
        agfc: $('#Agfc').val().trim()
    };

    // 3. Obtener Carrito (Productos + Cabecera de fecha/hora)
    const datosCarrito = obtenerDatosRaw(); 

    var fechaStr = datosCarrito.cabecera.fecha;
    var largo = fechaStr.length;
    if (largo <15)
        datosCarrito.cabecera.fecha = datosCarrito.cabecera.fecha + " to  " +datosCarrito.cabecera.fecha;
    // Validación básica
    if (!contacto.nombre || !contacto.telefono || !contacto.correo ||  !datosCarrito.cabecera.fecha) {
        alert("<?= Trd(18) ?>.");
        return;
    }

    // 4. Consolidar TODO el paquete
    const paqueteFinal = {
        cliente: contacto,
        lugar: ubicacion,
        cupon: cupon,
        reserva: {
            fecha: datosCarrito.cabecera.fecha,
            hInicio: datosCarrito.cabecera.hInicio,
            hFin: datosCarrito.cabecera.hFin,
            items: datosCarrito.items // Aquí ya van los adicionales anidados
        }
    };

    // 5. Envío mediante POST
    $.ajax({
        url: '<?php echo URL_API?>process_quote',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(paqueteFinal),
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-ID-CLIENT': '<?= ID_CLIENT ?>',
            'LNG':'<?= $_SESSION['Idioma'] ?>'
        },        
        beforeSend: function() {
            $('#btn_enviar_cotizacion').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> <?= Trd(19) ?>');
        },
        success: function(response) {
            if (response.status === 'success') {
                //$('#btn_enviar_cotizacion').prop('disabled', false).text('CONFIRMAR RESERVA');
                localStorage.removeItem('ds_jumper_cart');
                window.location.href = 'quote.php?Id=' + response.UUID;
            } else {
                alert("Error: " + response.message);
                $('#btn_enviar_cotizacion').prop('disabled', false).text('<?= Trd(20) ?>');
            }
        },
        error: function() {
            alert("<?= Trd(12) ?>.");
            $('#btn_enviar_cotizacion').prop('disabled', false).text('<?= Trd(20) ?>');
        }
    });
});


</script>
</body>
</html>