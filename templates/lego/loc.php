<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "loc"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 

    <div class="lego-divider">
        <span>
            <div class="mini-stud" style="background:var(--lego-red);"></div>
            <div class="mini-stud" style="background:var(--lego-orange);"></div>
            <div class="mini-stud" style="background:var(--lego-yellow);"></div>
            <div class="mini-stud" style="background:var(--lego-green);"></div>
            <div class="mini-stud" style="background:var(--lego-blue);"></div>
        </span>
    </div>
    <section class="mb-5">
        <div class="location-inner">
            <div class="loc-studs">
                <div class="stud"></div><div class="stud"></div>
                <div class="stud"></div><div class="stud"></div><div class="stud"></div>
            </div>
            <div class="row align-items-center g-4">
                <div class="col-md-5 px-4">
                    <h2 class="mb-4">🗺️ <?= Trd(1) ?></h2>
                    <p class="mb-3"><?php echo COBERTURA?></p>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> <?php echo DIRECCION1?></li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> <?php echo DIRECCION2?></li>
                        <li class="mb-2"><i class="fas fa-clock me-2"></i> Lun - Vie: 9:00 AM - 6:00 PM</li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <div class="ratio ratio-16x9 overflow-hidden" style="border-radius:12px;border:4px solid rgba(255,255,255,.3);box-shadow:0 8px 0 rgba(0,0,0,.2);">
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