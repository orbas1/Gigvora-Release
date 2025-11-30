<?php

return [
    'resolutions' => [
        '4k' => ['width' => 3840, 'height' => 2160, 'label' => 'Ultra HD (4K)'],
        '1080p' => ['width' => 1920, 'height' => 1080, 'label' => 'Full HD (1080p)'],
        '780p' => ['width' => 1280, 'height' => 780, 'label' => 'Vertical HD (780p)'],
        '480p' => ['width' => 854, 'height' => 480, 'label' => 'SD (480p)'],
        'auto' => ['width' => null, 'height' => null, 'label' => 'Auto'],
    ],

    'filters' => [
        'none' => [
            'label' => 'Neutral',
            'css' => 'none',
        ],
        'vivid' => [
            'label' => 'Vivid Boost',
            'css' => 'contrast(1.12) saturate(1.25) brightness(1.05)',
        ],
        'noir' => [
            'label' => 'Noir',
            'css' => 'grayscale(1) contrast(1.1)',
        ],
        'dusk' => [
            'label' => 'Dusk Glow',
            'css' => 'hue-rotate(316deg) saturate(1.15)',
        ],
    ],

    'stickers' => [
        'sparkles' => [
            'label' => 'Sparkles',
            'asset' => 'assets/frontend/studio/stickers/sparkles.png',
        ],
        'badge' => [
            'label' => 'Creator Badge',
            'asset' => 'assets/frontend/studio/stickers/badge.png',
        ],
        'heart' => [
            'label' => 'Heart Pop',
            'asset' => 'assets/frontend/studio/stickers/heart.png',
        ],
    ],

    'gifs' => [
        'celebrate' => [
            'label' => 'Celebrate',
            'asset' => 'assets/frontend/studio/gifs/celebrate.gif',
        ],
        'wow' => [
            'label' => 'Wow Burst',
            'asset' => 'assets/frontend/studio/gifs/wow.gif',
        ],
    ],

    'music' => [
        'pulse_bloom' => [
            'label' => 'Pulse Bloom',
            'asset' => 'assets/frontend/studio/audio/pulse-bloom.mp3',
            'attribution' => 'Pixabay License',
        ],
        'midnight_drive' => [
            'label' => 'Midnight Drive',
            'asset' => 'assets/frontend/studio/audio/midnight-drive.mp3',
            'attribution' => 'Pixabay License',
        ],
        'sunlit_wave' => [
            'label' => 'Sunlit Wave',
            'asset' => 'assets/frontend/studio/audio/sunlit-wave.mp3',
            'attribution' => 'Pixabay License',
        ],
    ],
];

