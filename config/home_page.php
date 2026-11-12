<?php

// Fixed content of the new homepage. Pictures are in public/web_frontend/assets/images/home.
return [

    // Main navigation either side of the logo: six categories, with shorter names to fit the bar
    // (the Search by Category cards are managed in the admin panel: Home Category Cards)
    'nav_left' => [
        ['label' => 'Drinkware', 'url' => 'category/drinkware-and-kitchenware'],
        ['label' => 'Apparel', 'url' => 'category/apparel-and-accessories'],
    ],
    'nav_right' => [
        ['label' => 'Technology', 'url' => 'category/technology-and-accessories'],
        ['label' => 'Office Stationery', 'url' => 'category/office-stationery-and-writing'],
        ['label' => 'Gift Sets', 'url' => 'category/customized-items'],
        ['label' => 'Bags & Travels', 'url' => 'category/bags-and-travels'],
    ],

    'why_left' => [
        ['icon' => 'bi-gift', 'title' => 'Curated Collections', 'text' => 'A wide range of premium and trending corporate gifts'],
        ['icon' => 'bi-pencil', 'title' => 'Custom Branding', 'text' => 'Add your logo, colors and identity'],
        ['icon' => 'bi-boxes', 'title' => 'Bulk Order Support', 'text' => 'Scalable solutions for teams and enterprises'],
    ],
    'why_right' => [
        ['icon' => 'bi-shield-check', 'title' => 'Quality Assurance', 'text' => 'Branded products with trusted quality'],
        ['icon' => 'bi-truck', 'title' => 'UAE & GCC Delivery', 'text' => 'Reliable and on-time delivery across the UAE & GCC'],
        ['icon' => 'bi-headset', 'title' => 'Dedicated Support', 'text' => 'From selection to delivery, we\'re with you at every step'],
    ],

    'new_arrivals_count' => 10,
];
