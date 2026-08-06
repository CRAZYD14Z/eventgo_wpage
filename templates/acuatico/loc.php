<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "loc"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 
<div class="aqua-divider"><span>📍 🗺️ 📍</span></div>
    <section class="location-section mb-5">
        <div class="row align-items-center g-4">
            <div class="col-md-5 px-4">
                <h2 class="mb-4">🗺️ <?= Trd(1) ?></h2>
                <p><?php echo COBERTURA?></p>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i><?php echo DIRECCION1?></li>
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i><?php echo DIRECCION2?></li>
                    <li class="mb-2"><i class="fas fa-clock me-2"></i> Lun - Vie: 9:00 AM - 6:00 PM</li>
                </ul>
            </div>
            <div class="col-md-7">
                <div class="ratio ratio-16x9 rounded-4 overflow-hidden" style="border:3px solid var(--sky); box-shadow:0 10px 30px rgba(0,105,148,.15);">
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
    </section>