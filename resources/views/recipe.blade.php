@php
    $directions = [
        'Prepare' =>
            'In a small bowl, combine the olive oil, mustard, garlic powder, paprika, minced garlic, lemon zest and juice and 1 teaspoon salt.',
        'Prepare Chicken' =>
            'Arrange the chicken pieces in a single layer in a 9-by-13-inch baking dish and sprinkle with salt. Pour the marinade over the chicken and use your hands to coat the chicken. Cover and let marinate in the refrigerator for 1 hour.',
        'Preheat Oven' => 'Preheat the oven to 400 degrees F.',
        'Season & Arrange' =>
            'Sprinkle the chicken with additional salt and some pepper, then roll the pieces around and arrange them skin-side up. Scatter the lemon quarters around the dish. Sprinkle the sugar on top of the chicken and dot the dish with the butter. Pour the broth and wine around the chicken.',
        'Cook for Juice' =>
            'Bake, basting with the juices about every 15 minutes, until an instant-read thermometer inserted in the chicken registers 165 degrees F, 40 to 45 minutes.',
        'Boil the Chicken' =>
            'Turn on the broiler. Broil the chicken until it’s deep golden brown and slightly charred in spots, 2 to 3 minutes. Spoon the liquid in the pan over the chicken and sprinkle the parsley on top.',
    ];
@endphp

<x-app class="gap-5">
    <div class="modal-card px-6 flex-1 h-500 gap-7">
        {{-- header --}}
        <div class="flex-center flex-col gap-1">
            <h1 class="">Lemon-Garlic Chicken</h1>
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
        <div class="flex-center border border-navbar-gray h-30 p-2">
            <div class="flex flex-center flex-1 h-full">
                <div class="flex-center flex-col items-start h-25 gap-3 text-left">
                    <h4 class="w-full">
                        <span class="font-normal">Ready In:</span>
                        2 hr 5 mins
                    </h4>
                    <h4 class="w-full">
                        <span class="font-normal">Prep Time:</span>
                        25 mins
                    </h4>
                </div>
            </div>
            <div class="flex flex-center flex-1 h-full border-x border-navbar-gray">
                <div class="flex-center flex-col items-start h-25 gap-3 text-left">
                    <h4 class="w-full">
                        <span class="font-normal">Level:</span>
                        <span class="text-accent">Easy</span>
                    </h4>
                    <h4 class="w-full">
                        <span class="font-normal">Ingredients:</span>
                        14
                    </h4>
                </div>
            </div>
            <div class="flex flex-center flex-1 h-full">
                <div class="flex-center flex-col items-start h-25 gap-3 text-left">
                    <h4 class="w-full">
                        <span class="font-normal">Cuisine:</span>
                        Malaysian
                    </h4>
                    <h4 class="w-full">
                        <span class="font-normal">Meal:</span>
                        Lunch
                    </h4>
                    <h4 class="w-full">
                        <span class="font-normal">Yield:</span>
                        4 servings
                    </h4>
                </div>
            </div>
        </div>
        {{-- description --}}
        <div class="px-3 w-full">
            <h2 class="text-heading-lg font-highlight font-medium">I love dishes that can be made in advance for a
                dinner party. This
                chicken is totally simple. You marinate
                it in the same dish you cook it in, then pop it in the oven about 45 minutes before you want to eat.
                It’s so
                easy and flavorful—a real crowd pleaser!</h2>
        </div>
        {{-- directions --}}
        <div class="flex flex-col text-left w-full p-2 gap-2">
            <h2 class="text-heading-lg">Directions:</h2>
            <ol class="list-decimal list-inside space-y-2 px-2 text-body-lg font-semibold">
                @foreach ($directions as $title => $step)
                    <li>
                        {{ $title }}: <span class=" font-medium">{{ $step }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
        {{-- aurthor --}}
        <div class="flex items-center justify-between rounded-xl w-full bg-gray px-6 py-3">
            <div class="flex gap-4">
                <img class="size-23" src="/images/profile-icons/katile-lee-biegel.png" alt="profile">
                <div class="flex justify-center flex-col text-left">
                    <h2 class="text-heading-lg">By Katile Lee Biegel</h2>
                    <h5 class="font-normal">Food Blogger & Recipe Creator</h5>
                </div>
            </div>
            <button class="flex-center button h-10 rounded-full bg-light-gray w-auto px-4">
                <h2 class="text-heading-lg">Follow</h2>
                <x-icons.user-plus class="text-gray-700" />
            </button>
        </div>
        {{-- reviews --}}
        <div class="flex flex-col items-start rounded-xl w-full bg-gray px-6 py-3">
            <div class="flex flex-col gap-1 items-start">
                <h2 class="text-heading-lg">132 Reviews</h2>
                <x-rating />
            </div>
        </div>
    </div>
    <div class="flex flex-col items-start justify-start mr-0 gap-5 w-1/4">
        <div class="modal-card w-full h-50"></div>
        <div class="modal-card w-full h-50"></div>
        <div class="modal-card w-full h-50"></div>
        <div class="flex-1"></div>
    </div>
</x-app>
