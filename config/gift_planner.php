<?php

// Choices on the Corporate Gifting Budget Planner. "tags" are matched against the
// products' Occasion Tags (admin: Product > Occasion Tags), so a new occasion only needs a row here.
return [

    'occasions' => [
        'eid' => ['label' => 'Ramadan & Eid', 'name' => 'Ramadan & Eid', 'image' => 'occasion-ramadan-eid.jpg', 'tags' => ['Eid Gifts', 'Ramadan']],
        'joining' => ['label' => 'Joining Kit', 'name' => 'Joining', 'image' => 'occasion-joining-kit.jpg', 'tags' => ['Employee Onboarding']],
        'anniversary' => ['label' => 'Work Anniversary', 'name' => 'Anniversary', 'image' => 'occasion-work-anniversary.jpg', 'tags' => ['Work Anniversary']],
        'festive' => ['label' => 'Festive Gifts', 'name' => 'Festive', 'image' => 'occasion-festive-gifts.jpg', 'tags' => ['Festive Gifts', 'Diwali Gifts', 'Christmas', 'New Year']],
        'client' => ['label' => 'Client Appreciation', 'name' => 'Client', 'image' => 'occasion-client-appreciation.jpg', 'tags' => ['Client Gifting']],
        'conference' => ['label' => 'Conference / Event', 'name' => 'Event', 'image' => 'occasion-conference-event.jpg', 'tags' => ['Trade Show', 'Conference']],
        'national' => ['label' => 'National Day', 'name' => 'National Day', 'icon' => 'bi-flag', 'tags' => ['National Day']],
        'other' => ['label' => 'Other', 'name' => 'Corporate', 'tags' => []],
    ],

    // Occasion look for the AI bundle pictures: small props in the scene, never extra gifts. "other" uses the customer's own words.
    'occasion_scenes' => [
        'eid' => 'a glowing Arabic lantern and a golden crescent moon beside the set, warm evening light',
        'joining' => 'a fresh desk setting with a small green plant, bright welcoming morning light',
        'anniversary' => 'a small gold star or laurel accent and a few gold confetti pieces, celebratory mood',
        'festive' => 'soft fairy lights and gentle golden sparkle in the background, festive warm glow',
        'client' => 'a single elegant fresh flower and a soft satin ribbon, refined mood',
        'conference' => 'a plain event lanyard and a softly blurred conference hall behind the set',
        'national' => 'accents in the UAE flag colours (red, green, white and black) and a small flag in the background',
    ],

    // Example bundles shown before the customer asks for recommendations (pictures in images/planner/examples)
    'examples' => [
        ['occasion' => 'eid', 'image' => 'eid.webp', 'title' => 'Ramadan & Eid Essentials', 'items' => 'Tech pouch, cotton cap & insulated bottle'],
        ['occasion' => 'anniversary', 'image' => 'anniversary.webp', 'title' => 'Anniversary Celebration Set', 'items' => 'Leather notebook, gold-rim tumbler & wireless charger'],
        ['occasion' => 'client', 'image' => 'client.webp', 'title' => 'Client Thank-You Box', 'items' => 'Metal pen, scented candle & linen notebook'],
        ['occasion' => 'joining', 'image' => 'joining.webp', 'title' => 'New Joiner Welcome Kit', 'items' => 'Canvas tote, ceramic mug, kraft notebook & steel bottle'],
    ],

    // Fixed pictures for a bundle by its badge, in public/web_frontend/assets/images/planner, e.g.
    // 'eid' => ['budget' => 'bundle-essentials.jpg']. None are set: a fixed picture shows gifts the bundle may not
    // contain, so bundles show their own products' photos (or the AI picture made from them).
    'bundle_images' => [],

    // One-click choices in the Other pop-up. They match the products' Occasion Tags like anything typed.
    'other_suggestions' => ['Retirement', 'Promotion', 'Farewell', 'Employee Awards', 'Team Offsite', 'Year-End Thank You', 'Emirati Women\'s Day', 'Mother\'s Day'],

    // Used when nothing is tagged for the chosen occasion
    'general_tag' => 'Corporate Gifts',

    // The customer types how many people and a budget, per person or in total (divided by the people).
    // Every product is priced for that many pieces; products with a larger minimum order are left out.
    'max_people' => 100000,
    'max_budget' => 10000000,

    // "tags" match the products' Preferences, "keywords" their name and AI tags
    'preferences' => [
        'eco' => ['label' => 'Eco-friendly', 'icon' => 'bi-tree', 'tags' => ['Eco Friendly', 'Sustainable']],
        'premium' => ['label' => 'Premium look', 'icon' => 'bi-gem', 'tags' => ['Premium Look']],
        'tech' => ['label' => 'Tech items', 'icon' => 'bi-laptop', 'keywords' => ['tech', 'powerbank', 'usb', 'charger', 'speaker', 'wireless']],
        'branding' => ['label' => 'Custom branding', 'icon' => 'bi-tag', 'branding' => true],
    ],

    // Share of the per-person budget each bundle aims for, and how many items it can hold
    'tiers' => [
        ['badge' => 'budget', 'label' => 'Budget Friendly', 'suffix' => 'Essentials Pack', 'description' => 'A thoughtful way to show you care.', 'share' => 0.7, 'items' => 2],
        ['badge' => 'popular', 'label' => 'Most Popular', 'suffix' => 'Premium Pack', 'description' => 'A perfect balance of elegance and utility.', 'share' => 0.85, 'items' => 3],
        ['badge' => 'premium', 'label' => 'Premium', 'suffix' => 'Luxury Hamper', 'description' => 'Make a lasting impression.', 'share' => 1, 'items' => 4],
    ],

    // OpenRouter: chooses and names bundles from real products, and creates their pictures. With no key or model the page uses the standard ones.
    'ai' => [
        'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),
        'key' => env('OPENROUTER_API_KEY'),
        'text_model' => env('OPENROUTER_TEXT_MODEL'),
        // Any model from OpenRouter's image endpoint (/images), e.g. black-forest-labs/flux-3-image-klein
        'image_model' => env('OPENROUTER_IMAGE_MODEL'),
        'text_timeout' => 20,
        'image_timeout' => 90,
        'texts_per_day' => (int) env('OPENROUTER_TEXTS_PER_DAY', 500),
        'images_per_day' => (int) env('OPENROUTER_IMAGES_PER_DAY', 50),
    ],

    // A saved AI choice is reused for the same inputs for this many days, then made fresh (so new products get a chance)
    'reuse_days' => 30,

    // Products compared when building bundles
    'shortlist_size' => 12,

    // No bundle costs less than this share of the per-person budget, so it suits the budget chosen
    'bundle_min_share' => 0.5,

    // Single gifts listed under Individual Products
    'products_limit' => 24,
];
