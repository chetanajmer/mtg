<?php

/*
|--------------------------------------------------------------------------
| About Us page: default wording
|--------------------------------------------------------------------------
|
| Staff can change all of this in the admin (About us editor); what they
| save is stored in abouts.content and replaces these defaults field by
| field. ":categories" is replaced with the live number of categories.
| Step names, categories and contact details are not here: they come from
| config/order_steps.php, the catalogue and config/company.php.
|
*/

return [
    'hero_eyebrow' => 'About us',
    'hero_title' => 'Corporate gifting, handled from idea to delivery',
    'hero_text' => 'More Than Giftz helps companies in the UAE choose, brand and deliver gifts for their teams, clients and events. You pick the products, we put your brand on them, and you follow every order online until it arrives.',
    'hero_image' => null,

    'what_title' => 'What we do',
    'what_intro' => 'Everything a company needs to send gifts that people actually keep, in one place.',
    'cards' => [
        ['title' => 'Gifts for every occasion', 'text' => ':categories categories, from drinkware and technology to bags, apparel and awards, for onboarding kits, client gifts, events and seasonal campaigns.'],
        ['title' => 'Your brand on every piece', 'text' => 'Screen printing, laser engraving, embroidery, debossing, UV and full-colour printing. You approve a design mockup before anything is made.'],
        ['title' => 'Clear pricing for bulk orders', 'text' => 'Every product shows its price and minimum order quantity, and many get cheaper as quantities grow, so you know the cost before you ask for a quote.'],
        ['title' => 'One account for your team', 'text' => 'Save product collections, keep your employee list with birthdays and work anniversaries, give colleagues their own login and download reports.'],
    ],

    'steps_title' => 'How an order works',
    'steps_intro' => 'The same steps for every order. You see each one on your order page and get an email whenever something needs you.',
    'steps' => [
        1 => 'Send a quote request from your cart. We confirm the products, quantities and price with you.',
        2 => 'We send a mockup with your logo. Approve it, ask for changes, or share your own artwork.',
        3 => 'Pay 50% to start. You get an invoice as soon as we receive it.',
        4 => 'See the physical sample before mass production, and approve it or ask for changes.',
        5 => 'Your gifts are made and branded, with an expected finish date you can see online.',
        6 => 'Every order is checked for quantity, branding and condition, then packed.',
        7 => 'Pay the remaining 50% and receive your paid-in-full invoice.',
        8 => 'We dispatch with a courier and tracking number, and confirm delivery.',
        9 => 'Your order is complete. Tell us how we did.',
    ],

    'categories_title' => 'What you can order',
    'categories_intro' => 'Browse a category to see products, branding options and prices.',

    'why_title' => 'Why companies work with us',
    'why_intro' => "Gifting for a whole company shouldn't mean chasing emails and spreadsheets.",
    'points' => [
        ['title' => "See it before it's made.", 'text' => 'Approve the design and the physical sample before production starts.'],
        ['title' => 'Know where every order is.', 'text' => 'Follow each step online and get an email when we need your decision.'],
        ['title' => 'Paperwork that adds up.', 'text' => 'An invoice for the advance and for the balance, and reports you can download any time.'],
        ['title' => 'Built for teams.', 'text' => 'Colleagues get their own login as admin, buyer or viewer, and see the same orders.'],
        ['title' => 'Never miss an occasion.', 'text' => "Keep your employees' birthdays and work anniversaries in one list and see what's coming up."],
        ['title' => 'Easy to reach.', 'text' => 'Call or email us, or simply reply to any email about your order.'],
    ],

    'cta_title' => 'Planning gifts for your team or clients?',
    'cta_text' => "Tell us what you need and we'll put together a quote.",
];
