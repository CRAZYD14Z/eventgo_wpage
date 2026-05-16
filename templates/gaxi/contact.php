    <?php 
        $api_url = URL_API."Traducciones_web";
        $data = json_encode(['program' => "contact"]);
        $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
    ?> 

    <?php
        if (isset($_POST['submit_contact'])) {
            //$to = "jdiaz_huerta@hotmail.com";
            $to = CORREO;
            $nombre   = strip_tags(trim($_POST['nombre']));
            $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $telefono = strip_tags(trim($_POST['telefono'])); // <--- Nueva variable
            $asunto   = strip_tags(trim($_POST['asunto']));
            $mensaje  = strip_tags(trim($_POST['mensaje']));


            $header = "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: text/html; charset=UTF-8\r\n";
            $header .= "CONTACTO PAGINA From: $email\r\n";            
                        // Incluimos el teléfono en el cuerpo del correo
            $cuerpo = "
            <html>
            <head>
                <title>".Trd(1)."</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .contacto { background-color: #f5f5f5; padding: 20px; border-radius: 5px; }
                    .campo { margin-bottom: 10px; }
                    .etiqueta { font-weight: bold; color: #333; }
                    .mensaje { background-color: white; padding: 15px; border-radius: 3px; margin-top: 10px; }
                </style>
            </head>
            <body>
                <div class='contacto'>
                    <h2>".Trd(1)."</h2>
                    
                    <div class='campo'>
                        <span class='etiqueta'>".Trd(2).":</span> $nombre
                    </div>
                    
                    <div class='campo'>
                        <span class='etiqueta'>".Trd(3).":</span> $email
                    </div>
                    
                    <div class='campo'>
                        <span class='etiqueta'>".Trd(4).":</span> $telefono
                    </div>
                    
                    <div class='campo'>
                        <span class='etiqueta'>".Trd(5).":</span> $asunto
                    </div>
                    
                    <div class='campo'>
                        <span class='etiqueta'>".Trd(6).":</span>
                        <div class='mensaje'>$mensaje</div>
                    </div>
                </div>
            </body>
            </html>";

            $rutaArchivo ='';
            $contenidoBase64 = '';
            $data = json_encode([
                'correo' => $to,
                'archivo_base64' => $contenidoBase64,
                'nombre_archivo' => $rutaArchivo,
                'Subject'=> $header,
                'Body'=> $cuerpo
            ]);

            $api_url = URL_API."sendmail";
            $data = json_decode(API($jwt,$api_url,$data,'POST'), true);   
            if ($data['send']){
                $respuestaCorreo = "<div class='alert alert-success' id='EmailMessage'>".Trd(7)."</div>";
            } else {
                $respuestaCorreo = "<div class='alert alert-danger' id='EmailMessage'>".Trd(8)."</div>";
            }
        }
    ?>

    <section class="mb-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg p-4">
                    <h2 class="text-center fw-bold mb-4"  style="color: var(--dark);"><?= Trd(9) ?></h2>
                    <div id="form-feedback" class="mb-3"></div>
                    <form id="contactForm" method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><?= Trd(10) ?></label>
                                <input type="text" name="nombre" class="form-control" placeholder="<?= Trd(11) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= Trd(12) ?></label>
                                <input type="email" name="email" class="form-control" placeholder="email@domain.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= Trd(13) ?></label>
                                <input type="tel" name="telefono" class="form-control"placeholder="##### #####" required>
                            </div>                            
                            <div class="col-12">
                                <label class="form-label"><?= Trd(14) ?></label>
                                <input type="text" name="asunto" class="form-control" placeholder="<?= Trd(15) ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label"><?= Trd(16) ?></label>
                                <textarea class="form-control" name="mensaje" rows="4" placeholder="<?= Trd(17) ?>" required></textarea >
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" name="submit_contact" class="btn btn-lg px-5"   style="background-color: var(--accent) ; color: var(--dark);"><?= Trd(18) ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <?php
        if (isset($respuestaCorreo)) {
            echo $respuestaCorreo;
        }
    ?>     

    <script>

    document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('contactForm');
    const feedback = document.getElementById('form-feedback');

    const telInput = form.querySelector('input[name="telefono"]');    
    const telRegex = /^[0-9\s+]{7,15}$/; 

    

    form.addEventListener('submit', function(event) {
        // Validación básica de email
        const emailInput = form.querySelector('input[type="email"]');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(emailInput.value)) {
            event.preventDefault();
            feedback.innerHTML = `<div class="alert alert-danger"><?= Trd(19) ?></div>`;
            return;
        }

        if (!telRegex.test(telInput.value)) {
            event.preventDefault();
            feedback.innerHTML = `<div class="alert alert-danger"><?= Trd(20) ?></div>`;
            return;
        }        

        // Si todo está bien, el formulario se envía por POST y PHP hace el resto.
        // Podrías usar AJAX aquí, pero el envío tradicional POST a la misma página es más sencillo.
    });
});

    </script>