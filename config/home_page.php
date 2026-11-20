<?php

// Fixed content of the new homepage. Pictures are in public/web_frontend/assets/images/home.
return [

    // Header (client's design, 19 Nov). Black bar either side of the logo: product lists from the search page
    // (Best Sellers, New Arrival and Sale follow the flags set on each product in the admin; prices are per piece in AED).
    'nav_left' => [
        ['label' => 'Best Sellers', 'url' => 'search?list=best-sellers'],
        ['label' => 'New Arrival', 'url' => 'search?list=new'],
    ],
    'nav_right' => [
        ['label' => 'Under 1-10', 'url' => 'search?list=under-10'],
        ['label' => 'Under 10-50', 'url' => 'search?list=10-50'],
        ['label' => 'Above 50', 'url' => 'search?list=above-50'],
        ['label' => 'Sale', 'url' => 'search?list=sale'],
    ],
    // The black top bar's language, currency and offers: admin Setup & Config > Header Top Bar (header_topbar_items)

    // Footer (client's design, 19 Nov)
    'footer' => [
        'title' => '“More than Giftz”  a Premium Promotional and Corporate gifts',
        'points' => [
            ['Competitive Market Pricing', 'Products at the best possible prices.'],
            ['Extensive Product Range', 'Over 1,000 products to choose from.'],
            ['Premium Global Brands', 'Access to internationally recognised brands.'],
            ['Customisation Expertise', 'Personalised gifts tailored to your brand.'],
            ['Ready Stock Availability', 'Quick access to a wide range of products.'],
            ['Quality Assurance', 'Carefully selected products with reliable quality.'],
            ['Creative Solutions', 'Unique ideas to make every gift memorable.'],
            ['Fast Turnaround', 'Efficient sourcing, branding and delivery.'],
            ['Regional Expertise', 'Serving clients across the Middle East, Africa and India.'],
            ['Bulk Order Capabilities', 'Solutions for corporate events and large-scale campaigns.'],
            ['Customer-Focused Service', 'Dedicated support from enquiry to delivery.'],
        ],
        // Pages the site doesn't have yet (Sustainability, Jobs, E-Catalog, Branding Methods, Our Locations, Blogs, FAQs)
        // point to the closest page that exists; change the url when the page is ready.
        'columns' => [
            'Company Info' => [
                ['About Us', 'aboutus'], ['Sustainability', 'search?q=eco'], ['Privacy Policy', 'privacy-policy'], ['Jobs', 'contactus'], ['Contact Us', 'contactus'],
            ],
            'Products' => [
                ['Home', '/'], ['E-Catalog', 'search?list=all'], ['Products', 'search?list=all'], ['Brands', 'brands'], ['Branding Methods', 'aboutus'],
            ],
            'Support' => [
                ['FAQS', 'contactus'], ['Our Locations', 'contactus'], ['Blogs', 'aboutus'],
            ],
        ],
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
