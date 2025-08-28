<x-app class="gap-5">
    <div class="modal-card flex-1 h-500">
        {{-- header --}}
        <div class="flex-center flex-col gap-1">
            <h1 class="text-display-sm">Lemon-Garlic Chicken</h1>
            <div class="flex-center flex-col">
                <h3>RECIPE BY KATILE LEE BIEGEL</h3>
                <h4 class="text-muted">Nov 29, 2023</h4>
            </div>
            <div class="flex-center flex-col mt-2 gap-1">
                <x-rating :value="4" />
                <h4 class="text-muted">132 Reviews</h4>
            </div>
        </div>
        {{-- media --}}
        <img src="https://food.fnr.sndimg.com/content/dam/images/food/fullset/2022/12/19/KC3213-katie-lee-biegel-lemon-garlic-chicken_s4x3.jpg.rend.hgtvcom.826.620.suffix/1671496800903.webp"
            alt="hero-image">
        {{-- summary --}}
        <div class="border border-muted">
            <p>This lemon-garlic chicken is a quick and easy weeknight dinner option. The chicken is marinated in a
                mixture of lemon juice, garlic, and herbs, then baked to perfection.</p>
        </div>
        {{-- description --}}
        {{-- directions --}}
        {{-- aurthor --}}
        {{-- reviews --}}
    </div>
    <div class="flex flex-col items-start justify-start mr-0 gap-5 w-1/4">
        <div class="modal-card w-full h-50"></div>
        <div class="modal-card w-full h-50"></div>
        <div class="modal-card w-full h-50"></div>
        <div class="flex-1"></div>
    </div>
</x-app>
