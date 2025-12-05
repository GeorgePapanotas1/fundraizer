<?php

return [
    'active_gateway' => env('PAYMENT_GATEWAY', 'fake'),
    'gateways' => [
        'fake' => [
            'payment_url' => 'https://fakepayment.donations.io',
        ],
    ],
];
