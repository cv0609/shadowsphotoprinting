<?php

return [
    'allowed_category_ids' => [1, 2, 3, 4, 18, 20],
    'allowed_category_slugs' => [
        'scrapbook-prints',
        'canvas-prints',
        'photo-enlargements',
        'prints-enlargements',
        'poster-prints',
        'wedding-package',
    ],

    // Photo Prints: 10x48/10x48 WB and 12x48/12x48 WB.
    'blocked_photo_print_ids' => [51, 52, 82, 83],

    // Photo Enlargements: 16x48, 20x60, 30x50, 30x60,
    // 36x48, 40x50 and 40x60.
    'blocked_photo_enlargement_ids' => [45, 129, 34, 33, 30, 29, 27, 26],

    // Posters: A0 and B0.
    'blocked_poster_ids' => [149, 150],

    // The listed 39.37x47.24 (100x120cm) item is not currently in the
    // products table; these patterns keep it blocked if it is added later.
    'blocked_product_title_patterns' => ['39.37', '47.24', '100x120', '100 x 120'],

    // Client-approved NZ Canvas sizes:
    // 12x12, 12x16, 12x20, 12x30, 12x40, 16x16, 16x20, 16x30,
    // 20x20, 20x30, 20x40, 30x20, 30x30, 30x40 and 40x30.
    'allowed_canvas_product_ids' => [
        14, 24, 13, 7, 167,
        12, 23, 11,
        22, 21, 19,
        8, 15, 10,
        16,
    ],
];
