
    <section class="mb-5 py-5 bg-white rounded-4 shadow-sm">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 px-4">
                    <h2 class="fw-bold mb-4">Nuestra Ubicación</h2>
                    <p class="text-muted"><?php echo COBERTURA?></p>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> <?php echo DIRECCION1?></li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> <?php echo DIRECCION2?></li>
                        <li class="mb-2"><i class="fas fa-clock text-primary me-2"></i> Lun - Vie: 9:00 AM - 6:00 PM</li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1422937611435!2d-73.98731968459391!3d40.75889497932681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480293%3A0x5117f70619c651b0!2sTimes%20Square!5e0!3m2!1ses!2scl!4v1625070000000!5m2!1ses!2scl" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

                            <iframe
                            width="600"
                            height="450"
                            style="border:0"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed/v1/place?key=<?php echo GOOGLE_API_KEY?>&q=place_id:<?php echo PLACE_ID?>">
                            </iframe>                            

                    </div>
                </div>
            </div>
        </div>
    </section>