
// Lógica simple para el contador del carrito
let itemCount = 0;
const cartCountElement = document.getElementById('cartCount');

// Esta función simula agregar un producto
//function agregarAlCarrito() {
//    itemCount++;
//    cartCountElement.innerText = itemCount;
//}


/*
const miProducto = {
    id: 125, // ID único de tu base de datos (MySQL)
    nombre: "Jumper Castillo Inflable",
    precio: 1200.00,
    imagen: "img/productos/jumper-01.jpg",
    url: "detalle_producto.php?id=125"
};

// Llamada a la función:
agregarAlCarrito(miProducto);

*/

// Ejemplo: Si quieres probarlo, puedes añadir un botón en tus tarjetas con: 
 





document.addEventListener('DOMContentLoaded', function() {
    // SECCION DE CALENDARIO 
    const fp = flatpickr("#calendarioRango", {
        mode: "range",
        inline: true,
        locale: "es",
        minDate: "today",
        dateFormat: "Y-m-d"
    });

    const hInicio = document.getElementById('hInicio');
    const hFin = document.getElementById('hFin');

    // 2. Llenar selectores con etiquetas AM/PM pero valores en 24h
    function llenarHoras() {
        for (let i = 0; i < 24; i++) {
            let valor24 = i.toString().padStart(2, '0') + ":00";
            let ampm = i >= 12 ? 'PM' : 'AM';
            let h12 = i % 12 || 12;
            let label = `${h12}:00 ${ampm}`;
            
            hInicio.innerHTML += `<option value="${valor24}">${label}</option>`;
            hFin.innerHTML += `<option value="${valor24}">${label}</option>`;
        }
    }
    llenarHoras();

    // 3. Sugerencia de +8 horas
    hInicio.addEventListener('change', function() {
        let h = parseInt(this.value.split(':')[0]);
        let nuevaH = (h + 8) % 24;
        hFin.value = nuevaH.toString().padStart(2, '0') + ":00";
    });

    // 4. Confirmar y formatear para datetime-local
    document.getElementById('btnConfirmar').addEventListener('click', function() {
        const fechas = fp.selectedDates;
        
        if (fechas.length < 1) {
            alert("Por favor selecciona dos fechas en el calendario.");
            return;
        }

        if (fechas.length === 1) {

            const f1 = fechas[0].toLocaleDateString('sv-SE'); // sv-SE devuelve YYYY-MM-DD
            const f2 = fechas[0].toLocaleDateString('sv-SE');

            // Los inputs datetime-local requieren el formato: YYYY-MM-DDTHH:mm
            document.getElementById('fechahorainicio').value = `${f1}T${hInicio.value}`;
            document.getElementById('fechahorafin').value = `${f2}T${hFin.value}`;            

        }
        else{
        // Formato ISO local: YYYY-MM-DD
            const f1 = fechas[0].toLocaleDateString('sv-SE'); // sv-SE devuelve YYYY-MM-DD
            const f2 = fechas[1].toLocaleDateString('sv-SE');

            // Los inputs datetime-local requieren el formato: YYYY-MM-DDTHH:mm
            document.getElementById('fechahorainicio').value = `${f1}T${hInicio.value}`;
            document.getElementById('fechahorafin').value = `${f2}T${hFin.value}`;
        }
        bootstrap.Modal.getInstance(document.getElementById('modalReserva')).hide();
    });

$(document).ready(function() {
    // 1. Al dar clic en "Seleccionar Fecha"
    $('#btnConfirmarFecha').on('click', function() {
        const fechaVal = $('#calendarioRango').val();
        
        if (fechaVal !== "") {
            // Actualizar el texto del resumen
            $('#textoFechaRango').text(fechaVal);
            
            // Animación: Ocultar calendario, mostrar resumen
            $('#contenedorCalendario').slideUp(400, function() {
                $('#resumenFecha').removeClass('d-none').hide().fadeIn();
            });

    //const carrito = obtenerCarrito();
    //carrito.forEach(item => {
    //    alert(item.id)
    //});

    const carrito = obtenerCarrito();

    const datos = {
    items: carrito.map(item => ({
        id: item.id,
        precio: item.precio,
        cantidad: item.cantidad,
        existencia: item.existencia
    })),

    SD: $('#FI').val(),
    ED: $('#FF').val(),
    SH: '08:00',
    EH: '16:00'
};

$.ajax({
    url: url_api+'cart_update',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(datos),
    headers: {
        'Authorization': 'Bearer ' + token
    },
    success: function(response) {
        console.log('Éxito:', response);
        // Procesar respuesta
        cart_update(response.data);
        
    },
    error: function(xhr, status, error) {
        console.error('Error:', error);
        mostrarError('Error al revalidar el carrito');
    }
});    


            

        } else {
            // Opcional: Algún feedback visual si no hay fecha
            $('#calendarioRango').addClass('is-invalid').focus();
        }
    });

    // 2. Al dar clic en el icono pequeño (Reabrir)
    $('#btnReabrirCalendario').on('click', function() {
        if ($('#contenedorCalendario').is(':visible')) {
            // Si está visible, lo oculta y muestra el resumen
            $('#contenedorCalendario').slideUp(400, function() {
                $('#resumenFecha').fadeIn(300);
            });
        } else {
            // Si está oculto, muestra el calendario y oculta el resumen
            $('#resumenFecha').fadeOut(300, function() {
                $('#contenedorCalendario').slideDown(400);
            });
        }
    });

    // Quitar error del input al escribir
    $('#calendarioRango').on('change', function() {
        $(this).removeClass('is-invalid');
    });

    setearComponentesCabecera();
    
    renderizarCarrito()

    // Opcional: Si cambian las horas después de seleccionar fecha, actualizar cabecera
    $('#hInicio, #hFin').on('change', function() {
        const fechaActual = $('#textoFechaRango').text();
        const cOde = $('#CUPON').val();
        const tYpe = $('#TIPOCUPON').val();
        const vAl = $('#DESCUENTO').val();            
        if(fechaActual !== "-- / -- / --") {
            guardarCabecera(fechaActual, $('#hInicio').val(), $('#hFin').val(),cOde,tYpe,vAl);
        }
    });    



    var $target = $("#EmailMessage");

    // Validamos si el ID existe en la página
    if ($target.length > 0) {
        $('html, body').animate({
            scrollTop: $target.offset().top
        }, 800);
    }
    
    //pintarLogCarrito();
});    

    // Valores iniciales
    hInicio.value = "08:00";
    hInicio.dispatchEvent(new Event('change'));

});

function cart_update(items){
    
    let carrito = obtenerCarrito();
    items.forEach(product => {
        //alert(product.id)
        const existe = carrito.find(item => item.id === product.id);
        if (existe) {
            existe.precio = product.precio;
            existe.existencia = product.existencia;
        }
    });
    guardarCarrito(carrito);    
}


const STORAGE_KEY = 'ds_jumper_cart';

// 1. LA BASE: Leer directamente del LocalStorage (Nivel 0)
function obtenerDatosRaw() {
    const datos = localStorage.getItem(STORAGE_KEY);
    const estructuraInicial = { 
        items: [], 
        cabecera: { fecha: "", hInicio: "", hFin: "" },
        cupon: { code:"", type:"", val:"" } // Estructura por defecto
    };

    if (!datos) return estructuraInicial;

    try {
        const parsed = JSON.parse(datos);
        // Mezclamos con la estructura inicial para asegurar que cabecera exista
        return { ...estructuraInicial, ...parsed };
    } catch (e) {
        return estructuraInicial;
    }
}

// 2. OBTENER ITEMS: Extrae solo el arreglo de productos
function obtenerCarrito() {
    const data = obtenerDatosRaw();
    return data.items || []; 
}

// 3. OBTENER CABECERA: Extrae solo el objeto de fechas/horas
function obtenerCabecera() {
    const data = obtenerDatosRaw();
    return data.cabecera || { fecha: "", hInicio: "", hFin: "" };
}

// 4. GUARDAR CARRITO (Actualiza solo productos, mantiene cabecera)
function guardarCarrito(nuevosItems) {
    let data = obtenerDatosRaw();
    data.items = nuevosItems;
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    renderizarCarrito(); // Solo llama a la UI
    //pintarLogCarrito();

}

// 5. GUARDAR CABECERA (Actualiza periodo, mantiene productos)
function guardarCabecera(fecha, hInicio, hFin) {
    let data = obtenerDatosRaw();
    data.cabecera = {
        fecha: fecha,
        hInicio: hInicio,
        hFin: hFin
    };
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
}

function guardarCupon(code,type,val) {
    let data = obtenerDatosRaw();
    data.cupon = {
        code: code, 
        type: type, 
        val: val 
    };
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));

    animarCarritoConMensaje('Cupon ');

    renderizarCarrito();
    //pintarLogCarrito();
}

// --- 2. AGREGAR ELEMENTO ---
function agregarAlCarrito(producto,cantidad) {
    let carrito = obtenerCarrito();
    
    // Verificar si ya existe para solo sumar cantidad
    const existe = carrito.find(item => item.id === producto.id);
    if (existe) {
        existe.cantidad += cantidad;
    } else {
        producto.cantidad = cantidad;
        carrito.push(producto);
    }
    guardarCarrito(carrito);

    animarCarritoConMensaje('Producto')

}

function animarCarritoConMensaje(producto) {
    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.textContent = `✓ ${producto} agregado !!`;
    notificacion.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #ffffff;
        color: black;
        padding: 10px 20px;
        border-radius: 5px;
        z-index: 9999;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
    `;
    
    document.body.appendChild(notificacion);
    
    // Animar entrada
    setTimeout(() => {
        notificacion.style.opacity = '1';
        notificacion.style.transform = 'translateY(0)';
    }, 100);
    
    // Animar el carrito
    const cartIcon = document.querySelector('#cartIcon i');
    cartIcon.style.transform = 'scale(1.2)';
    cartIcon.style.transition = 'transform 0.2s ease';
    
    setTimeout(() => {
        cartIcon.style.transform = 'scale(1)';
    }, 200);
    
    // Remover notificación
    setTimeout(() => {
        notificacion.style.opacity = '0';
        notificacion.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            document.body.removeChild(notificacion);
        }, 300);
    }, 2000);
}

function agregarExtraAItem(itemId, itemRl,Name,Price,Image,Url) {


    const extra = {
        id: itemRl, // ID único de tu base de datos (MySQL)
        nombre: Name,
        precio: Price,
        imagen: Image,
        url: Url
    };    

    
    let carrito = obtenerCarrito();

    const item = carrito.find(p => p.id === itemId);
    
    if (item) {

        if (!item.adicionales) item.adicionales = [];
        
        // Verificamos si ya tiene este extra específico para no duplicarlo
        const existeExtra = item.adicionales.find(a => a.id === extra.id);
        
        if (!existeExtra) {

            item.adicionales.push(extra);
            // GuardarCarrito ya se encarga de actualizar LocalStorage y refrescar la UI
            guardarCarrito(carrito); 

            animarCarritoConMensaje('Producto')            

        } else {

            console.log("Este extra ya está incluido en este producto.");
        }
    }
}

// --- 3. INCREMENTAR / DECREMENTAR ---
function cambiarCantidad(id, delta) {
    let carrito = obtenerCarrito();
    const item = carrito.find(p => p.id === id);    
    if (item) {
        if (item.cantidad + delta > item.existencia)
            return;
        
        item.cantidad += delta;
        // Si la cantidad llega a 0, lo eliminamos
        if (item.cantidad <= 0) {
            eliminarDelCarrito(id);
        } else {
            guardarCarrito(carrito);
        }
        
            if (typeof actualID !== 'undefined') {                
                MAX_STOCK += (delta * -1) ;
                $('#stock-val').html(MAX_STOCK);
            }        
    }
}

// --- 4. ELIMINAR ELEMENTO ---
function eliminarDelCarrito(id) {
    let carrito = obtenerCarrito();
    carrito = carrito.filter(item => item.id !== id);
    guardarCarrito(carrito);
}

function eliminarAdicionalDeItem(itemId, adicionalId) {
    let carrito = obtenerCarrito();
    
    // Buscamos el producto principal asegurando que los IDs coincidan como strings
    const item = carrito.find(p => String(p.id) === String(itemId));
    
    if (item && item.adicionales) {
        // Filtramos comparando también como strings para mayor seguridad
        const totalAntes = item.adicionales.length;
        item.adicionales = item.adicionales.filter(a => String(a.id) !== String(adicionalId));
        
        // Verificamos si realmente se borró algo antes de guardar
        if (item.adicionales.length !== totalAntes) {
            guardarCarrito(carrito);
            //console.log(`Extra ${adicionalId} eliminado del item ${itemId}`);
        } else {
            //console.warn("No se encontró el adicional con ID:", adicionalId);
        }
    }
}

// --- 5. CALCULAR TOTALES (Dinero y Cantidades) ---
function obtenerTotales() {
    const carrito = obtenerCarrito();

    const calculos = carrito.reduce((acc, item) => {
        let precioItem = item.precio;
        // Sumar precios de adicionales si existen
        if (item.adicionales) {
            precioItem += item.adicionales.reduce((sum, ad) => sum + (ad.precio*1), 0);
        }
        
        acc.subtotal += (precioItem * item.cantidad);
        acc.cantidadTotal += item.cantidad;
        return acc;
    }, { subtotal: 0, cantidadTotal: 0 });

    return {
        subtotal: calculos.subtotal,
        total: calculos.subtotal, // Aquí puedes aplicar impuestos o descuentos si gustas
        cantidadTotal: calculos.cantidadTotal
    };
}


function renderizarCarrito() {
    const carrito = obtenerCarrito();
    const $contenedor = $('#modalReserva .px-3.pb-4'); // El div donde van los productos
    const totales = obtenerTotales();
    let Descuento = 0;
    
    // Limpiar contenedor pero mantener el label
    $contenedor.html('<label class="small fw-bold text-muted text-uppercase d-block mb-3" style="font-size: 0.65rem;">Artículos en el carrito</label>');

    if (carrito.length === 0) {
        $contenedor.append('<p class="text-center text-muted my-4">El carrito está vacío</p>');
    }

    carrito.forEach(item => {

        let htmlListaExtras = '';

        if (item.adicionales && item.adicionales.length > 0) {
            // Contenedor con fondo sutil para agrupar los extras del producto
            htmlListaExtras = '<div class="mt-2 border-top pt-2 bg-light rounded-2 p-2 shadow-sm">';
            htmlListaExtras += '<p class="fw-bold mb-2 text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;"><i class="fa-solid fa-plus-circle me-1 text-success"></i> COMPLEMENTOS SELECCIONADOS</p>';

            item.adicionales.forEach(extra => {
                // Si no tienes imagen para el extra, puedes usar un placeholder o un icono
                const imgExtra = extra.imagen ?  extra.imagen : 'https://via.placeholder.com/35';
                
                htmlListaExtras += `
                    <div class="d-flex justify-content-between align-items-center mb-2 animate__animated animate__fadeIn">
                        <div class="d-flex align-items-center">
                            <img src="${imgExtra}" class="rounded-circle border me-2" 
                                style="width: 35px; height: 35px; object-fit: cover; background: #fff;">
                            
                            <div>
                                <span class="d-block fw-bold text-dark" style="font-size: 0.75rem; line-height: 1;">${extra.nombre}</span>
                                <span class="text-muted" style="font-size: 0.7rem;">+$${extra.precio}</span>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-link text-danger p-0 border-0 ms-2" 
                                onclick="eliminarAdicionalDeItem(${item.id}, '${extra.id}')"
                                title="Quitar complemento">
                            <i class="fa-solid fa-circle-xmark fs-6"></i>
                        </button>
                    </div>`;
            });
            htmlListaExtras += '</div>';
        }    
/*
        const html = `
            <div class="card border-0 shadow-sm mb-3 rounded-3 overflow-hidden" data-id="${item.id}">
                <div class="row g-0 align-items-start">
                    <div class="col-4 p-2">
                        <a href="${item.url}" class="d-block text-center">
                            <img src="${item.imagen}" class="img-fluid rounded-2" style="object-fit: contain; max-height: 75px;">
                        </a>
                    </div>
                    <div class="col-8">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <a href="#url-del-producto" class="text-decoration-none text-dark">
                                                <h6 class="mb-0 fw-bold small">${item.nombre}</h6>
                                            </a>
                                            <p class="text-muted mb-2 small" style="font-size: 0.7rem;">Incluye insumos</p>
                                        </div>                            
                                
                                <button type="button" class="btn btn-link text-danger p-0 border-0" onclick="eliminarDelCarrito(${item.id})">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="btn-group btn-group-sm border rounded-pill bg-light">
                                    <button class="btn btn-light py-0 px-2 fw-bold" onclick="cambiarCantidad(${item.id}, -1)">-</button>
                                    <span class="px-2 bg-light fw-bold align-self-center">${item.cantidad}</span>
                                    <button class="btn btn-light py-0 px-2 fw-bold" onclick="cambiarCantidad(${item.id}, 1)">+</button>
                                </div>
                                <span class="fw-bold text-dark">$${(item.precio * item.cantidad).toLocaleString('es-MX')}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
*/

        // Definimos el overlay solo si no hay existencia
        const overlayNoDisponible = item.existencia <= 0 
            ? `<div class="not-available-overlay">NO DISPONIBLE</div>` 
            : '';

        // Aplicamos un filtro de escala de grises a la imagen si no hay stock
        const styleImagen = item.existencia <= 0 ? 'filter: grayscale(1); opacity: 0.5;' : '';

        const html = `
            <div class="card border-0 shadow-sm mb-3 rounded-3 overflow-hidden ${item.existencia <= 0 ? 'bg-light' : ''}" data-id="${item.id}">
                <div class="row g-0 align-items-start">
                    <div class="col-4 p-2 position-relative"> <a href="${item.url}" class="d-block text-center">
                            <img src="${item.imagen}" class="img-fluid rounded-2" 
                                style="object-fit: contain; max-height: 75px; ${styleImagen}">
                        </a>
                        ${overlayNoDisponible} 
                    </div>
                    <div class="col-8">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <a href="${item.url}" class="text-decoration-none text-dark">
                                        <h6 class="mb-0 fw-bold small">${item.nombre}</h6>
                                    </a>
                                    <p class="text-muted mb-2 small" style="font-size: 0.7rem;">
                                        ${item.existencia <= 0 ? '<span class="text-danger fw-bold">Agotado</span>' : 'Incluye insumos'}
                                    </p>
                                </div>                               
                                <button type="button" class="btn btn-link text-danger p-0 border-0" onclick="eliminarDelCarrito(${item.id})">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="btn-group btn-group-sm border rounded-pill bg-light ${item.existencia <= 0 ? 'opacity-50' : ''}">
                                    <button class="btn btn-light py-0 px-2 fw-bold" onclick="cambiarCantidad(${item.id}, -1)" ${item.existencia <= 0 ? 'disabled' : ''}>-</button>
                                    <span class="px-2 bg-light fw-bold align-self-center">${item.cantidad}</span>
                                    <button class="btn btn-light py-0 px-2 fw-bold" onclick="cambiarCantidad(${item.id}, 1)" ${item.existencia <= 0 ? 'disabled' : ''}>+</button>
                                </div>
                                <span class="fw-bold text-dark">$${(item.precio * item.cantidad).toLocaleString('es-MX')}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        //html+=  htmlListaExtras;
        $contenedor.append(html+htmlListaExtras);
    });

    // Actualizar Totales en el Footer
    $('#Articulos').html(`Subtotal (${totales.cantidadTotal} artículos)`);
    cartCountElement.innerText = totales.cantidadTotal;
    $('#SubTotal').html(`$${totales.subtotal.toLocaleString('es-MX')} MXN`);

    const data = obtenerDatosRaw();

    $('#CUPON').val(data.cupon.code);
    $('#TIPOCUPON').val(data.cupon.type);
    $('#DESCUENTO').val(data.cupon.val);         

    if (data.cupon.cupon != ""){
        $('#EtiquetaCupon').html(` Descuento ( ${data.cupon.code} )`);
        if (data.cupon.type == 'percentage'){
            Descuento = totales.total *  (data.cupon.val / 100);
        }
        else{
            Descuento =  data.cupon.val;
        }
        
    }
    else{
        $('#EtiquetaCupon').html(` Descuento ( No Aplicado )`);
    }
    $('#Descuento').html(`$${Descuento.toLocaleString('es-MX')} MXN`);

    if (Descuento < totales.total)
        totales.total = totales.total - Descuento;

    $('.modal-footer .fs-5 .text-primary').html(`$${totales.total.toLocaleString('es-MX')} MXN`);
}

function setearComponentesCabecera() {
    const datos = obtenerDatosRaw();

    if (datos.cabecera) {
        const { fecha, hInicio, hFin } = datos.cabecera;
        const {  cOde, tYpe, vAl } = datos.cupon;

        // 1. Setear el texto del resumen (si ya se había confirmado)
        
        if (fecha && fecha !== "") {
            $('#textoFechaRango').text(fecha);
            //$('#calendarioRango').val(fecha);
            $('#calendarioRango')[0]._flatpickr.setDate(fecha);
            

            const calendario = $('#calendarioRango')[0]._flatpickr;
            const fechas = calendario.selectedDates;

            if (fechas.length === 2) {
                const fechaInicial = fechas[0];
                const fechaFinal = fechas[1];
                
                // Formato YYYY-MM-DD
                const formatoFecha = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };
                
                const fechaInicialStr = formatoFecha(fechaInicial);
                const fechaFinalStr = formatoFecha(fechaFinal);
                
                //console.log('Fecha inicial:', fechaInicialStr);
                //console.log('Fecha final:', fechaFinalStr);
                $('#FI').val(fechaInicialStr); // Objeto Date
                $('#FF').val(fechaFinalStr);   // Objeto Date            
            }   
            
            
            if (fechas.length === 1) {
                const fechaInicial = fechas[0];
                //const fechaFinal = fechas[1];
                
                // Formato YYYY-MM-DD
                const formatoFecha = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };
                
                const fechaInicialStr = formatoFecha(fechaInicial);
                const fechaFinalStr = formatoFecha(fechaInicial);
                
                //console.log('Fecha inicial:', fechaInicialStr);
                //console.log('Fecha final:', fechaFinalStr);
                $('#FI').val(fechaInicialStr); // Objeto Date
                $('#FF').val(fechaFinalStr);   // Objeto Date            
            }             



            // Ocultar calendario y mostrar resumen visualmente
            $('#contenedorCalendario').hide();
            $('#resumenFecha').removeClass('d-none').show();
        }

        // 2. Setear los selects de hora
        if (hInicio) $('#hInicio').val(hInicio);
        if (hFin) $('#hFin').val(hFin);
        
        $('#CUPON').val(cOde);
        $('#TIPOCUPON').val(tYpe);
        $('#DESCUENTO').val(vAl);    
            

    }

}

$('#btnConfirmarFecha').on('click', function() {
    const fechaVal = $('#calendarioRango').val();
    const hInicio = $('#hInicio').val();
    const hFin = $('#hFin').val();
    const cOde = $('#CUPON').val();
    const tYpe = $('#TIPOCUPON').val();
    const vAl = $('#DESCUENTO').val();    
    
    if (fechaVal !== "") {
        // Guardar en la sesión del navegador
        guardarCabecera(fechaVal, hInicio, hFin,cOde,tYpe,vAl);
        

        const calendario = $('#calendarioRango')[0]._flatpickr;
        const fechas = calendario.selectedDates;

        if (fechas.length === 2) {
            const fechaInicial = fechas[0];
            const fechaFinal = fechas[1];
            
            // Formato YYYY-MM-DD
            const formatoFecha = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            const fechaInicialStr = formatoFecha(fechaInicial);
            const fechaFinalStr = formatoFecha(fechaFinal);
            
            //console.log('Fecha inicial:', fechaInicialStr);
            //console.log('Fecha final:', fechaFinalStr);
            $('#FI').val(fechaInicialStr); // Objeto Date
            $('#FF').val(fechaFinalStr);   // Objeto Date            
        }

        // Efectos visuales
        $('#textoFechaRango').text(fechaVal);
        $('#contenedorCalendario').slideUp(400, function() {
            $('#resumenFecha').removeClass('d-none').hide().fadeIn();
        });
    } else {
        alert("Selecciona un rango de fechas");
    }
});


function pintarLogCarrito() {
    const data = obtenerDatosRaw();
    
    console.group("%c🛒 ESTADO DEL CARRITO", "color: #ffffff; background: #2196f3; padding: 4px 10px; border-radius: 4px; font-weight: bold;");
    
    // 1. Mostrar Cabecera (Periodo y Horas)
    console.log("%c📅 Periodo de Reserva:", "font-weight: bold; color: #1565c0;");
    if (data.cabecera) {
        console.table([data.cabecera]);        
    } else {
        console.warn("No hay datos de cabecera configurados.");
    }

    if (data.cupon) {
        console.table([data.cupon]);        
    } else {
        console.warn("No hay datos de cabecera configurados.");
    }    

    // 2. Mostrar Productos y sus Extras
    console.log("%c📦 Productos en Carrito:", "font-weight: bold; color: #2e7d32;");
    if (data.items && data.items.length > 0) {
        // Creamos una versión para la tabla que resuma los extras para que sea legible
        const resumenTabla = data.items.map(item => ({
            ID: item.id,
            Producto: item.nombre,
            Cant: item.cantidad,
            Precio: `$${item.precio}`,
            Extras: item.adicionales ? item.adicionales.length : 0,
            Subtotal: `$${(item.precio * item.cantidad).toFixed(2)}`
        }));
        
        console.table(resumenTabla);

        // 3. Detalle profundo de extras (si existen)
        data.items.forEach(item => {
            if (item.adicionales && item.adicionales.length > 0) {
                console.groupCollapsed(`+ Detalles Extras de: ${item.nombre}`);
                console.table(item.adicionales);
                console.groupEnd();
            }
        });
    } else {
        console.log("El carrito está vacío.");
    }

    // 4. Totales Finales
    const totales = obtenerTotales();
    console.log(`%cTOTAL FINAL: $${totales.total.toFixed(2)}`, "font-weight: bold; font-size: 14px; color: #d32f2f;");
    
    console.groupEnd();
}