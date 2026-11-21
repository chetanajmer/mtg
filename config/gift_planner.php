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

    // What the page shows on opening, before a team size and budget are chosen
    'defaults' => ['team' => '51-100', 'budget' => '201-300'],

    // Pictures for a bundle by its badge, in public/web_frontend/assets/images/planner.
    // Occasions without any show the photos of the bundle's products.
    'bundle_images' => [
        'eid' => ['budget' => 'bundle-essentials.jpg', 'popular' => 'bundle-premium.jpg', 'premium' => 'bundle-luxury.jpg'],
    ],

    // One-click choices in the Other pop-up. They match the products' Occasion Tags like anything typed.
    'other_suggestions' => ['Retirement', 'Promotion', 'Farewell', 'Employee Awards', 'Team Offsite', 'Year-End Thank You', 'Emirati Women\'s Day', 'Mother\'s Day'],

    // Used when nothing is tagged for the chosen occasion
    'general_tag' => 'Corporate Gifts',

    // min and max people. The price uses min, the larger of min and the product's MOQ.
    'team_sizes' => [
        '1-25' => ['label' => '1–25 people', 'min' => 1, 'max' => 25],
        '26-50' => ['label' => '26–50 people', 'min' => 26, 'max' => 50],
        '51-100' => ['label' => '51–100 people', 'min' => 51, 'max' => 100],
        '101-250' => ['label' => '101–250 people', 'min' => 101, 'max' => 250],
        '251-500' => ['label' => '251–500 people', 'min' => 251, 'max' => 500],
        '500+' => ['label' => '500+ people', 'min' => 500, 'max' => 100000],
    ],

    // Budget per person in AED
    'budgets' => [
        'under-50' => ['label' => 'Under AED 50', 'max' => 50],
        '50-100' => ['label' => 'AED 50–100', 'max' => 100],
        '101-200' => ['label' => 'AED 101–200', 'max' => 200],
        '201-300' => ['label' => 'AED 201–300', 'max' => 300],
        '301-500' => ['label' => 'AED 301–500', 'max' => 500],
        '500+' => ['label' => 'AED 500+', 'max' => 1000],
    ],

    // "tags" match the products' Preferences, "keywords" their name and AI tags
    'preferences' => [
        'eco' => ['label' => 'Eco-friendly', 'icon' => 'bi-tree', 'tags' => ['Eco Friendly', 'Sustainable']],
        'premium' => ['label' => 'Premium look', 'icon' => 'bi-gem', 'tags' => ['Premium Look']],
        'tech' => ['label' => 'Tech items', 'icon' => 'bi-laptop', 'keywords' => ['tech', 'powerbank', 'usb', 'charger', 'speaker', 'wireless']],
        'branding' => ['label' => 'Custom branding', 'icon' => 'bi-tag', 'branding' => true],
    ],

    // Share of the budget each bundle aims for, and how many items it can hold
    'tiers' => [
        ['badge' => 'budget', 'label' => 'Budget Friendly', 'suffix' => 'Essentials Pack', 'description' => 'A thoughtful way to show you care.', 'share' => 0.6, 'items' => 2],
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
];
