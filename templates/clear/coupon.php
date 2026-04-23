    <section class="mb-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg p-4">
                    <h2 class="text-center fw-bold mb-4">Obten un cupón de descuento</h2>
                    <div id="form-feedback" class="mb-3"></div>
                    <form id="contactForm" method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" placeholder="Tu nombre" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder="email@ejemplo.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono de contacto</label>
                                <input type="tel" name="telefono" class="form-control"placeholder="Ej: +52 33 1234 5678" required>
                            </div>                            
                            <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="terminos" >
                                        <label class="form-check-label" for="flexCheckDefault">
                                            By submitting this form, I agree to the privacy policy and terms and conditions and give my express written consent to D's Jumpers to be contacted via text and phone call, even if this number is a wireless number or if I am presently listed on a Do Not Call list. I understand that I may be contacted by telephone, email, text message or mail regarding marketing services and that I may be called using automatic dialing equipment. I understand that I can reply STOP to STOP communications at any time. Message and data rates may apply. My consent does not require purchase.
                                        </label>
                                    </div>                                
                                
                            </div>

                            <div class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_WEB?>"></div>

                            <div class="col-12 text-center mt-4">
                                <button type="submit" name="submit_contact" class="btn btn-primary btn-lg px-5">Solicitar Cupon ahora!</button>
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
            feedback.innerHTML = `<div class="alert alert-danger">Debes aceptar los términos o marcar la casilla para continuar.</div>`;
            return;
        }        

        if (!emailRegex.test(emailInput.value)) {
            event.preventDefault();
            feedback.innerHTML = `<div class="alert alert-danger">Por favor, ingresa un correo válido.</div>`;
            return;
        }

        if (!telRegex.test(telInput.value)) {
            event.preventDefault();
            feedback.innerHTML = `<div class="alert alert-danger">El formato del teléfono no es válido.</div>`;
            return;
        }        


    
    
    // 1. Obtener la respuesta del reCAPTCHA
    const captchaResponse = grecaptcha.getResponse();

    // 2. Validar si está vacío
    if (captchaResponse.length === 0) {
        event.preventDefault(); // Detiene el envío del formulario
        feedback.innerHTML = `<div class="alert alert-danger">Por favor, marca la casilla "No soy un robot".</div>`;
        return;
    }        


    });
});

    </script>