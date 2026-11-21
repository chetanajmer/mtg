<?php

// The banner strip under the header (every page with the site header except the home page).
// Admin: Banners > Page Banners. Each page can have several banners; a different one shows on every visit.
// A page with no banners of its own uses the Default banners; with none at all, no strip is shown.
// 'paths' are matched with request()->is(), first match wins.
return [
    'pages' => [
        'default' => ['label' => 'Default (pages without their own banners)', 'paths' => []],
        'category' => ['label' => 'Category pages', 'paths' => ['category/*', 'subcategory/*']],
        'product' => ['label' => 'Product pages', 'paths' => ['product/*']],
        'search' => ['label' => 'Search results', 'paths' => ['search']],
        'compare' => ['label' => 'Compare Gifts', 'paths' => ['compare-products']],
        'planner' => ['label' => 'Budget Planner', 'paths' => ['corporate-gifting-budget-planner']],
        'membership' => ['label' => 'Annual Contract', 'paths' => ['annual-corporate-membership']],
        'about' => ['label' => 'About Us', 'paths' => ['aboutus']],
        'contact' => ['label' => 'Contact Us', 'paths' => ['contactus']],
        'policies' => ['label' => 'Policy pages', 'paths' => ['privacy-policy', 'return-policy', 'shippment-policy']],
        'track-order' => ['label' => 'Track Order', 'paths' => ['track-order', 'track-order/*']],
        'cart' => ['label' => 'Cart and quote sent', 'paths' => ['cart', 'enquiry/success/*']],
        'request-quote' => ['label' => 'Request a Quote', 'paths' => ['request-quote']],
        'dashboard' => ['label' => 'Portal: Dashboard', 'paths' => ['corporate-portal']],
        'orders' => ['label' => 'Portal: Orders and order details', 'paths' => ['orders', 'orders/*']],
        'addresses' => ['label' => 'Portal: Addresses', 'paths' => ['corporate-addresses']],
        'branding-assets' => ['label' => 'Portal: Branding Assets', 'paths' => ['branding-assets', 'branding-assets/*']],
        'collections' => ['label' => 'Portal: Saved Collections', 'paths' => ['collections-all', 'collections/*']],
        'employees' => ['label' => 'Portal: Employees', 'paths' => ['corporate-employees', 'corporate-employees/*']],
        'reports' => ['label' => 'Portal: Reports', 'paths' => ['corporate-reports', 'corporate-reports/*']],
        'account-settings' => ['label' => 'Portal: Account Settings', 'paths' => ['corporate-account-settings']],
    ],

    // Shown to the admin: the strip is this shape on a desktop screen (1498 x 195 in the client's design)
    'recommended_size' => '1920 x 250 px',
];
