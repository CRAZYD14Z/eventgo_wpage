<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "loc"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 
    <section class="mb-5 py-5 bg-white rounded-4 shadow-sm">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 px-4">
                    <h2 class="fw-bold mb-4"><?= Trd(1) ?></h2>
                    <p class="text-muted"><?php echo COBERTURA?></p>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> <?php echo DIRECCION1?></li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> <?php echo DIRECCION2?></li>
                        <li class="mb-2"><i class="fas fa-clock text-primary me-2"></i> Lun - Vie: 9:00 AM - 6:00 PM</li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow">

                        <?php if (PLACE_ID == ''){?>
                            <iframe
                                width="600"
                                height="450"
                                style="border:0"
                                loading="lazy"
                                allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps/embed/v1/place?key=<?php echo GOOGLE_API_KEY; ?>&q=<?php echo LAT; ?>,<?php echo LNG; ?>">
                            </iframe>
                        <?php }else{?>
                            <iframe
                            width="600"
                            height="450"
                            style="border:0"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed/v1/place?key=<?php echo GOOGLE_API_KEY?>&q=place_id:<?php echo PLACE_ID?>">
                            </iframe>
                        <?php }?>                    



                    </div>
                </div>
            </div>
        </div>
    </section>