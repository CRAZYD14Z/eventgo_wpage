<?php 
    $api_url = URL_API."Traducciones_web";
    $data = json_encode(['program' => "us"]);
    $Traducciones = json_decode(API($jwt,$api_url,$data,'GET'), true);
?> 
<div class="lego-divider">
        <span>
            <div class="mini-stud" style="background:var(--lego-red);"></div>
            <div class="mini-stud" style="background:var(--lego-yellow);"></div>
            <div class="mini-stud" style="background:var(--lego-blue);"></div>
            <div class="mini-stud" style="background:var(--lego-green);"></div>
            <div class="mini-stud" style="background:var(--lego-orange);"></div>
        </span>
    </div>

    <section class="mb-5" id="nosotros">
        <div class="about-inner">
            <div class="about-studs">
                <div class="stud"></div><div class="stud"></div>
                <div class="stud"></div><div class="stud"></div><div class="stud"></div>
            </div>
            <h2 class="section-title" style="color:var(--lego-red);">🟥 <?= Trd(1) ?></h2>
            <p class="section-sub" style="color:var(--lego-blue);">— ¡conoce a los constructores! —</p>
            <div class="row align-items-center g-5">
                <div class="col-md-8">
                    <p class="lead mb-4">
                        🧱 <?php echo NOSOTROS?>
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
        </div>
    </section>