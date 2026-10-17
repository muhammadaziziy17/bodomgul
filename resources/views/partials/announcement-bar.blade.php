@php($announcement = \App\Support\SiteSettings::brand()['announcement'] ?? null)

@if (filled($announcement))
    <div class="safia-ticker" data-announcement aria-label="E'lon va aksiyalar">
        <div class="safia-ticker__inner">
            <div class="safia-ticker__track">
                {{-- Marquee uzluksiz aylanishi uchun kontent ikki nusxada yoziladi. --}}
                @for ($copy = 0; $copy < 2; $copy++)
                    @for ($i = 0; $i < 3; $i++)
                        <span class="safia-ticker__item">
                            <i class="bi bi-gift-fill" aria-hidden="true"></i> {{ $announcement }}
                        </span>
                        <span class="safia-ticker__dot" aria-hidden="true">•</span>
                    @endfor
                @endfor
            </div>
        </div>
    </div>
@endif
