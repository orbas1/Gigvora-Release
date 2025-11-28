<?php

return [
    'id' => 'gigs',
    'name' => __('Gigs'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Home",
    'fields' => [
        [
            'id'            => 'theme_style',
            'type'          => 'radio',
            'class'         => '',
            'label_title'   => __('Theme style'),
            'options'       => [
                'tk-work-boxed'        => __('Boxed'),
                'tk-work-fullwidth'    => __('Full_Width'),
            ],
            'default'       => 'tk-work-fullwidth',  
        ],
        [
            'id'            => 'sub-heading',
            'type'          => 'text',
            'value'         => 'Boost Your Working Flow',
            'class'         => '',
            'label_title'   => __('Sub heading'),
            'placeholder'   => __('Enter sub heading'),
        ],
        [
            'id'            => 'heading',
            'type'          => 'text',
            'value'         => 'Your One-Stop Online Marketplace for Everything You Need',
            'class'         => '',
            'label_title'   => __('Heading'),
            'placeholder'   => __('Enter heading'),
        ],
        [
            'id'            => 'marketing_btn_text',
            'type'          => 'text',
            'value'         => 'Marketing',
            'class'         => '',
            'label_title'   => __('Button text'),
            'placeholder'   => __('Enter button text'),
        ],
        [
            'id'            => 'paragraph',
            'type'          => 'editor',
            'value'         => 'Your premier online marketplace. Find quality products and services, connect with trusted sellers, and enjoy a seamless shopping experience today.',
            'class'         => '',
            'label_title'   => __('Paragraph'),
            'placeholder'   => __('Enter paragraph'),
        ],
        [
            'id'            => 'btn_text',
            'type'          => 'text',
            'value'         => 'Explore More Gigs',
            'class'         => '',
            'label_title'   => __('Button text'),
            'placeholder'   => __('Enter button text'),
        ],
    ]
];
