<style>
    .comic-card {
        background: var(--white);
        border: 4px solid var(--ink);
        box-shadow: 10px 10px 0 var(--ink);
        border-radius: 0;
        position: relative;
        overflow: hidden;
    }

    .comic-card::before {
        content: "¡OFERTA!";
        position: absolute;
        top: 10px;
        right: -30px;
        background: var(--yellow);
        color: var(--ink);
        padding: 5px 40px;
        transform: rotate(45deg);
        font-family: 'Bangers', cursive;
        border: 2px solid var(--ink);
        z-index: 10;
    }

    .comic-title {
        font-family: 'Bangers', cursive;
        font-size: 3rem;
        color: var(--blue);
        text-transform: uppercase;
        -webkit-text-stroke: 1px var(--ink);
        text-shadow: 3px 3px 0 var(--yellow);
        letter-spacing: 2px;
    }

    .comic-label {
        font-family: 'Bangers', cursive;
        letter-spacing: 1px;
        color: var(--ink);
        font-size: 1.1rem;
    }

    .comic-input {
        border: 3px solid var(--ink) !important;
        border-radius: 0 !important;
        font-family: 'Comic Neue', cursive;
        font-weight: 700;
        background-color: var(--white);
        box-shadow: 4px 4px 0 rgba(0,0,0,0.1);
    }

    .comic-input:focus {
        background-color: #fffde0 !important;
        box-shadow: 5px 5px 0 var(--ink) !important;
        outline: none;
    }

    .btn-action {
        font-family: 'Bangers', cursive;
        font-size: 1.5rem;
        background: var(--red);
        color: white;
        border: 3px solid var(--ink);
        border-radius: 0;
        box-shadow: 6px 6px 0 var(--ink);
        transition: all 0.2s;
        text-transform: uppercase;
    }

    .btn-action:hover {
        transform: scale(1.05) rotate(-1deg);
        background: var(--blue);
        color: var(--yellow);
        box-shadow: 8px 8px 0 var(--ink);
    }

    .form-check-label {
        font-family: 'Comic Neue', cursive;
        font-size: 0.85rem;
        line-height: 1.2;
        color: #444;
    }

    .form-check-input {
        border: 2px solid var(--ink);
        border-radius: 0;
    }

    .form-check-input:checked {
        background-color: var(--blue);
        border-color: var(--ink);
    }
</style>

<section class="mb-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card comic-card p-4 p-lg-5">
                <h2 class="text-center comic-title mb-4">¡Obtén un cupón de descuento!</h2>
                
                <div id="form-feedback" class="mb-3"></div>
                
                <form id="contactForm" method="POST" action="">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label comic-label">Nombre del Héroe</label>
                            <input type="text" name="nombre" class="form-control comic-input" placeholder="TU NOMBRE AQUÍ..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label comic-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control comic-input" placeholder="EMAIL@EJEMPLO.COM" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label comic-label">Teléfono de contacto</label>
                            <input type="tel" name="telefono" class="form-control comic-input" placeholder="EJ: +52 33 1234 5678" required>
                        </div>                            
                        
                        <div class="col-12">
                            <div class="form-check bg-light p-3 border border-dark">
                                <input class="form-check-input ms-0 me-2" type="checkbox" value="" id="terminos" required>
                                <label class="form-check-label" for="terminos">
                                    Al enviar este formulario, acepto la política de privacidad y los términos y condiciones. Doy mi consentimiento expreso a <strong>D's Jumpers</strong> para ser contactado vía texto o llamada (incluso si estoy en la lista Do Not Call). Entiendo que puedo responder STOP para cancelar en cualquier momento. ¡LA AVENTURA COMIENZA AQUÍ!
                                </label>
                            </div>                                
                        </div>

                        <div class="col-12 d-flex justify-content-center">
                            <div class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_WEB?>"></div>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" name="submit_contact" class="btn btn-action px-5 py-3 w-100 w-md-auto">
                                <i class="fa-solid fa-bolt me-2"></i> ¡SOLICITAR CUPÓN AHORA!
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>