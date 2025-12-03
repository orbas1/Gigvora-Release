<?php

return [
    'banned_phrases' => [
        'severe' => [
            'kill all',
            'racial genocide',
            'commit terrorism',
        ],
        'standard' => [
            'hate speech',
            'violent threat',
            'criminal marketplace',
        ],
    ],
    'escalation_minutes' => [
        1 => 24 * 60,
        2 => 48 * 60,
        3 => 72 * 60,
        4 => 7 * 24 * 60,
        5 => 30 * 24 * 60,
    ],
];
