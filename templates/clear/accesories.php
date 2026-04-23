    <section class="mb-5">
        <h2 class="section-title"><?php echo $Title;?></h2>
        <div class="row g-4">
            <?php
                foreach ($data['Accesories'] as $Accesory) {
                    
                    $Accesory['Image'] = URL_IMAGES.$Accesory['Image'];
                    $Name = '"'.$Accesory['Name'].'"';
                    $Price = '"'.$Accesory['Price'].'"';
                    $Image = '"'.$Accesory['Image'].'"';
                    $Url = '""';
                    echo "<div class='col-6 col-md-4 col-lg-2'>
                        <p  class='cat-card'>
                            <img height='150px' src='{$Accesory['Image']}' alt='{$Accesory['Name']}'>
                            <b>{$Accesory['Name']}</b>
                            <br>
                            $ ".$Accesory['Price']."
                            <br>
                            <button type='button' onclick='agregarExtraAItem({$Accesory['Producto_rp']},{$Accesory['Producto_r']},{$Name},{$Price},{$Image},{$Url})' class='btn btn-dark w-100 fw-bold py-2 shadow-sm rounded-3'>
                                <i class='fa-solid fa-cart-plus'></i> Agregar a Carrito 
                            </button>
                        </p>
                    </div>";
                }
            ?>
        </div>
    </section>