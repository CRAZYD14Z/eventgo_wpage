<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "us"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 
<section class="about-section mb-5" id="nosotros">
        <div class="section-center">
            <h2 class="section-title"><?= Trd(1) ?>
                <span class="title-tag">¡Con todo el amor!</span>
            </h2>
        </div>
        <div class="row align-items-center g-4">
            <div class="col-md-8">
                <p class="lead mb-4">
                    🎂 <?php echo NOSOTROS?>
                </p>
                <p>
                    <?php echo MISIONVISION?>
                </p>
            </div>
            <div class="col-md-4">
                <div class="about-logo-container">
                    <div id="movingLogo" class="moving-logo">
                        <img height="128px" src="<?php echo COMPANY_LOGO?>" alt="<?php echo COMPANY_NAME?>">
                    </div>
                </div>
            </div>
        </div>
    </section>
