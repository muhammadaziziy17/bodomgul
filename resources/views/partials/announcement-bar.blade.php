@php($announcement = config('bodomgul.brand.announcement'))

<div class="safia-ticker" data-announcement aria-label="E'lon va aksiyalar">
    <div class="safia-ticker__inner">
        <div class="safia-ticker__track">
            <span class="safia-ticker__item"><i class="bi bi-gift-fill" aria-hidden="true"></i> {{ $announcement }}</span>
            <span class="safia-ticker__dot">•</span>
            <span class="safia-ticker__item">mobil ilova</span>
            <span class="safia-ticker__dot">•</span>
            <span class="safia-ticker__item"><i class="bi bi-cup-hot-fill" aria-hidden="true"></i> Qahva va issiq kruassanlar</span>
            <span class="safia-ticker__dot">•</span>
            <span class="safia-ticker__item">mobil ilova</span>
            <span class="safia-ticker__dot">•</span>
            <span class="safia-ticker__item"><i class="bi bi-flower1" aria-hidden="true"></i> Har kuni yangi va tabiiy shirinliklar</span>
            <span class="safia-ticker__dot">•</span>
            <span class="safia-ticker__item">mobil ilova</span>
            <span class="safia-ticker__dot">•</span>
            <span class="safia-ticker__item"><i class="bi bi-gift-fill" aria-hidden="true"></i> {{ $announcement }}</span>
            <span class="safia-ticker__dot">•</span>
            <span class="safia-ticker__item">mobil ilova</span>
        </div>
    </div>
</div>
