<?php

/*
|--------------------------------------------------------------------------
| Order workflow steps
|--------------------------------------------------------------------------
|
| The single definition of the 10 order steps. Every page, email and status
| label reads from here. "type" decides which form and actions a step has
| (see App\Services\OrderWorkflow). "customer" is the name customers see.
|
*/

return [
    1 => ['title' => 'Enquiry / Order', 'customer' => 'Order confirmed', 'type' => 'review',
          'hint' => 'Check quantities, prices and branding, then confirm the order with the customer.'],
    2 => ['title' => 'Mockup Design & Approval', 'customer' => 'Design approval', 'type' => 'approval',
          'hint' => 'Upload the mockup, send it to the customer and record their decision. Each version is kept.'],
    3 => ['title' => 'Advance Payment (50%)', 'customer' => 'Advance payment', 'type' => 'payment', 'share' => 0.5,
          'hint' => 'Record the 50% advance once it is received.'],
    4 => ['title' => 'Sample Production & Approval', 'customer' => 'Sample approval', 'type' => 'approval',
          'hint' => 'Upload photos of the physical sample, send them and record the customer\'s decision.'],
    5 => ['title' => 'Mass Production', 'customer' => 'In production', 'type' => 'production',
          'hint' => 'Start production with an expected finish date, then mark it finished.'],
    6 => ['title' => 'Quality Check & Packing', 'customer' => 'Quality check & packing', 'type' => 'checklist',
          'hint' => 'Confirm the QC checklist and packed quantity, with photos.'],
    7 => ['title' => 'Balance Payment (50%)', 'customer' => 'Balance payment', 'type' => 'payment', 'share' => 0.5,
          'hint' => 'Record the remaining 50% once it is received.'],
    8 => ['title' => 'Delivery', 'customer' => 'Delivery', 'type' => 'shipment',
          'hint' => 'Mark the order dispatched with courier and tracking number, then delivered with proof.'],
    9 => ['title' => 'Project Completed', 'customer' => 'Completed', 'type' => 'closure',
          'hint' => 'Close the project once everything is delivered and paid.'],
    10 => ['title' => 'Customer Rating', 'customer' => 'Your feedback', 'type' => 'feedback',
          'hint' => 'Record the customer\'s rating and comment.'],
];
