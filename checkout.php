<?php 
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
<?php require_once TEMPLATE.'checkout.php'; ?>
</div>

<?php require_once TEMPLATE.'social.php'; ?>

<?php require_once TEMPLATE.'cart.php'; ?>

<?php require_once TEMPLATE.'scripts.php'; ?>
<?php require_once 'scripts.php'; ?>
<script src="<?php echo URL_BASE."/".TEMPLATE;?>js/idx-template.js"></script>
<script src="<?php echo URL_BASE."/";?>js/general.js"></script>
<script>

function cargarResumenCheckout() {
    const data = obtenerDatosRaw(); // La función que blindamos antes
    const $contenedor = $('#checkout_items');
    
    // 1. Llenar Cabecera
    if (data.cabecera) {
        //alert(data.cabecera.fecha)
        $('#resumen_fecha').text(data.cabecera.fecha || 'No seleccionada');
        $('#resumen_hInicio').text(data.cabecera.hInicio || '--:--');
        $('#resumen_hFin').text(data.cabecera.hFin || '--:--');
    }

    // 2. Llenar Items y Subitems
    $contenedor.empty();
    if (data.items.length === 0) {
        $contenedor.html('<p class="text-center py-4 text-muted">No hay artículos</p>');
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
                            <i class="fa-solid fa-circle-plus me-1"></i> Personalizar pedido
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

    if (data.cupon.cupon != ""){
        $('#chEtiquetaCupon').html(` Descuento ( ${data.cupon.code} )`);
        if (data.cupon.type == 'percentage'){
            Descuento = totales.total *  (data.cupon.val / 100);
        }
        else{
            Descuento =  data.cupon.val;
        }
        
    }
    else{
        $('#chEtiquetaCupon').html(` Descuento ( No Aplicado )`);
    }
    $('#chDescuento').html(`$${Descuento.toLocaleString('es-MX')}`);

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

    $listaContenedor.html('<div class="text-center py-2"><i class="fa-solid fa-spinner fa-spin"></i> Cargando...</div>');

    $.ajax({
        url: '<?php echo URL_API?>get_accesories/', // Tu endpoint de API
        type: 'GET',
        data: { producto_id: itemId },
        headers: {
            'Authorization': 'Bearer ' + token
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
                $listaContenedor.html('<p class="small text-muted text-center">No hay accesorios disponibles.</p>');
            }
        },
        error: function(xhr) {
            console.error("Error API:", xhr.status);
            $listaContenedor.html('<p class="small text-danger text-center">Error al cargar accesorios.</p>');
        }
    });    
}


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
            $campo.after(`<div class="invalid-feedback">${campo.nombre} es obligatorio</div>`);
            
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
            $('#email_cliente').after('<div class="invalid-feedback">Ingresa un correo electrónico válido (ej: usuario@dominio.com)</div>');
        }
    }
    
    // Validación adicional para dominios comunes
    let dominio = email.split('@')[1];
    if (dominio && !dominio.includes('.')) {
        isValid = false;
        $('#email_cliente').addClass('is-invalid');
        if ($('#email_cliente').next('.invalid-feedback').length === 0) {
            $('#email_cliente').after('<div class="invalid-feedback">El dominio del correo no es válido</div>');
        }
    }
}    

    // Validaciones específicas para CP (solo números)
    let cp = $('#cp_cliente').val().trim();
    if (cp !== '' && !/^\d+$/.test(cp)) {
        isValid = false;
        $('#cp_cliente').addClass('is-invalid');
        if ($('#cp_cliente').next('.invalid-feedback').length === 0) {
            $('#cp_cliente').after('<div class="invalid-feedback">El código postal debe contener solo números</div>');
        }
    }

    let cp2 = $('#cp_evento').val().trim();
    if (cp2 !== '' && !/^\d+$/.test(cp)) {
        isValid = false;
        $('#cp_evento').addClass('is-invalid');
        if ($('#cp_evento').next('.invalid-feedback').length === 0) {
            $('#cp_evento').after('<div class="invalid-feedback">El código postal debe contener solo números</div>');
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
        tax: $('#tax').val().trim(),
        cupon: $('#cupon').val().trim()
    };

    const cupon = {
        cupon: $('#CUPON').val().trim(),
        tipocupon: $('#TIPOCUPON').val().trim(),
        descuento: $('#DESCUENTO').val().trim()
    };    


    // 3. Obtener Carrito (Productos + Cabecera de fecha/hora)
    const datosCarrito = obtenerDatosRaw(); 

    var fechaStr = datosCarrito.cabecera.fecha;
    var largo = fechaStr.length;
    if (largo <15)
        datosCarrito.cabecera.fecha = datosCarrito.cabecera.fecha + " to  " +datosCarrito.cabecera.fecha;
    // Validación básica
    if (!contacto.nombre || !contacto.telefono || !contacto.correo ||  !datosCarrito.cabecera.fecha) {
        alert("Por favor completa el nombre, teléfono y la fecha del evento.");
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
            'Authorization': 'Bearer ' + token
        },        
        beforeSend: function() {
            $('#btn_enviar_cotizacion').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Procesando...');
        },
        success: function(response) {
            if (response.status === 'success') {
                // Limpiar carrito tras éxito
                //$('#btn_enviar_cotizacion').prop('disabled', false).text('CONFIRMAR RESERVA');
                localStorage.removeItem('ds_jumper_cart');
                window.location.href = 'quote.php?Id=' + response.UUID;
            } else {
                alert("Error: " + response.message);
                $('#btn_enviar_cotizacion').prop('disabled', false).text('CONFIRMAR RESERVA');
            }
        },
        error: function() {
            alert("Error de conexión con el servidor.");
            $('#btn_enviar_cotizacion').prop('disabled', false).text('CONFIRMAR RESERVA');
        }
    });
});


</script>
</body>
</html>