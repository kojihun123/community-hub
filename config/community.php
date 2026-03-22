<?php

return [
    'popular' => [
        'days' => env('POPULAR_POST_DAYS', 7),
        'minimum_score' => env('POPULAR_POST_MINIMUM_SCORE', 30),
    ],
];