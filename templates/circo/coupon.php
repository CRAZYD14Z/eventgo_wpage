<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "coupon"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 

    <section class="mb-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg p-4">
                    <h2 class="text-center fw-bold mb-4"><?= Trd(1) ?></h2>
                    <div id="form-feedback" class="mb-3"></div>
                    <form id="contactForm" method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><?= Trd(2) ?></label>
                                <input type="text" name="nombre" class="form-control" placeholder="<?= Trd(3) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= Trd(4) ?></label>
                                <input type="email" name="email" class="form-control" placeholder="email@ejemplo.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= Trd(5) ?></label>
                                <input type="tel" name="telefono" class="form-control"placeholder="##### #####" required>
                            </div>                            
                            <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="terminos" >
                                        <label class="form-check-label" for="flexCheckDefault">
                                            <?php if ($_SESSION['Idioma'] == 'es'){?>
                                            Al enviar este formulario, acepto la política de privacidad y los términos y condiciones, y doy mi consentimiento expreso por escrito a D's Jumpers para que me contacten por mensaje de texto y llamada telefónica, incluso si este número es de telefonía móvil o si estoy registrado en la lista de "No llamar". Entiendo que pueden contactarme por teléfono, correo electrónico, mensaje de texto o correo postal con respecto a servicios de marketing y que pueden llamarme utilizando equipos de marcación automática. Entiendo que puedo responder "STOP" para detener las comunicaciones en cualquier momento. Pueden aplicarse tarifas de mensajes y datos. Mi consentimiento no requiere compra.
                                            <?php }else{?>
                                            By submitting this form, I agree to the privacy policy and terms and conditions and give my express written consent to D's Jumpers to be contacted via text and phone call, even if this number is a wireless number or if I am presently listed on a Do Not Call list. I understand that I may be contacted by telephone, email, text message or mail regarding marketing services and that I may be called using automatic dialing equipment. I understand that I can reply STOP to STOP communications at any time. Message and data rates may apply. My consent does not require purchase.
                                            <?php }?>
                                        </label>
                                    </div>                                
                                
                            </div>

                            <div class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_WEB?>"></div>

                            <div class="col-12 text-center mt-4">
                                <button type="submit" name="submit_contact" class="btn btn-primary btn-lg px-5"><?= Trd(6) ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
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
        const checkboxInput = document.getElementById('terminos');

        if (!checkboxInput.checked) {
            event.preventDefault();
            feedback.innerHTML = `<div class="alert alert-danger"><?= Trd(7) ?></div>`;
            return;
        }        

        if (!emailRegex.test(emailInput.value)) {
            event.preventDefault();
            feedback.innerHTML = `<div class="alert alert-danger"><?= Trd(8) ?>div>`;
            return;
        }

        if (!telRegex.test(telInput.value)) {
            event.preventDefault();
            feedback.innerHTML = `<div class="alert alert-danger"><?= Trd(9) ?></div>`;
            return;
        }        


    
    
    // 1. Obtener la respuesta del reCAPTCHA
    const captchaResponse = grecaptcha.getResponse();

    // 2. Validar si está vacío
    if (captchaResponse.length === 0) {
        event.preventDefault(); // Detiene el envío del formulario
        feedback.innerHTML = `<div class="alert alert-danger"><?= Trd(10) ?></div>`;
        return;
    }        


    });
});

    </script>