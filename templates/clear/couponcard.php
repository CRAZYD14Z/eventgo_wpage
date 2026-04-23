
<button id="couponTab" class="animate-bounce shadow-lg" onclick="showCardFromTab()">
    🎁 ¡DAME MI CUPÓN!
</button>


<div class="card floating-card" id="couponCard">
    <div class="card-header-custom d-flex justify-content-end p-2">            
        <button type="button" class="btn-close" aria-label="Close" onclick="closeCardManually()"></button>
    </div>
    
    <div class="card-body text-center pt-0 pb-4 px-4">
        
        <div class="mb-3">
            <img src="<?php echo COMPANY_LOGO ?>" width="120px" alt="Logo" class="rounded-circle shadow-sm">
        </div>

        <h2 class="font-mercado mb-3">¡Descuento Exclusivo!</h2>
        
        <p class="card-text text-muted mb-4">No te vayas sin tu regalo. Solicita tu cupón de bienvenida ahora.</p>
        <div class="d-grid">                
            <a href="coupon/get-coupon" class="btn btn-vibrant-red rounded-pill shadow-lg animate-bounce">
                ¡Solicitar mi Cupón!
            </a>
        </div>
    </div>
</div>