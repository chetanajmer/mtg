<?php

// Fixed content of the new homepage. Pictures are in public/web_frontend/assets/images/home.
return [

    // The six most popular categories, most popular first. Real subcategories are looked up by slug and skipped when missing.
    'category_cards' => [
        ['title' => 'Drinkware & Kitchenware', 'image' => 'card-drinkware-kitchenware.webp', 'category' => 'drinkware-and-kitchenware', 'subcategories' => ['bottles-220', 'mugs-618', 'tumblers-flasks-885', 'coasters-109']],
        ['title' => 'Apparel & Accessories', 'image' => 'card-apparel-accessories.webp', 'category' => 'apparel-and-accessories', 'subcategories' => ['t-shirts-856', 'winterwear-636', 'caps-293', 'accessories-160']],
        ['title' => 'Technology & Accessories', 'image' => 'card-technology-accessories.webp', 'category' => 'technology-and-accessories', 'subcategories' => ['headphones-and-ear-phones-577', 'mobile-accessories-245', 'power-banks-621', 'speakers-752']],
        ['title' => 'Office Stationery & Writing', 'image' => 'card-office-stationery-writing.webp', 'category' => 'office-stationery-and-writing', 'subcategories' => ['notebooks-702', 'metal-aluminum-pens-890', 'desktop-items-826', 'pen-sets-227']],
        ['title' => 'Gift Sets', 'image' => 'card-gift-sets.webp', 'category' => 'customized-items', 'subcategories' => ['customized-gift-sets-581']],
        ['title' => 'Bags & Travels', 'image' => 'card-bags-travels.webp', 'category' => 'bags-and-travels', 'subcategories' => ['backpack-bags-430', 'travel-trolley-bags-109', 'briefcase-business-bags-637', 'pouches-sleeve-665']],
    ],

    // Main navigation either side of the logo: the same six categories as the cards above, with shorter names to fit the bar
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
