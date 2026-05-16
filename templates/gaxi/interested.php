    <section class="mb-5">
        <h2 class="section-title"><?php echo $Title;?></h2>
        <div class="swiper swiperCategories">
            <div class="swiper-wrapper">

                <?php
                
                    foreach ($data['UpSelling'] as $UpSelling) {
                        $UpSelling['Image'] = URL_IMAGES.'/products_images/thumbnails/'.$UpSelling['Image'];
                        $URL = str_replace(" ","-",$UpSelling['Name']);
                        echo "  <div class='swiper-slide'>
                                    <a href='".URL_BASE."/product/{$URL}' class='cat-card'>

                                    <img height='150px' src='{$UpSelling['Image']}' alt='{$UpSelling['Name']}'> 
                                    <br>
                                    <b>{$UpSelling['Name']}</b>
                                    <br> $ {$UpSelling['Price']}
                                    </a>
                                </div>";
                    }
                
                ?>
                

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>