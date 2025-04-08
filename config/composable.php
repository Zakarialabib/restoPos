<?php

declare(strict_types=1);

return [
    'filter_steps' => [
        'category_selection',
        'ingredient_filtering',
        'price_calibration',
        'workflow_validation'
    ],
    'workflow' => [
        'validation_rules' => [
            'minimum_ingredients' => 3,
            'max_preparation_time' => 30
        ]
    ]
];
