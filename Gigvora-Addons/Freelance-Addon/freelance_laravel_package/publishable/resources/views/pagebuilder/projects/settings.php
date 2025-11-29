<?php

return [
    'id' => 'projects',
    'name' => __('Projects'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Home",
    'fields' => [
        [
            'id'            => 'sub-heading',
            'type'          => 'text',
            'value'         => 'Categories for Every Shopper',
            'class'         => '',
            'label_title'   => __('Sub heading'),
            'placeholder'   => __('Enter sub heading'),
        ],
        [
            'id'            => 'heading',
            'type'          => 'text',
            'value'         => 'Find the optimal <span>employment opportunity</span> that aligns with your skillset',
            'class'         => '',
            'label_title'   => __('Heading'),
            'placeholder'   => __('Enter heading'),
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
            'id'            => 'no_of_projects',
            'type'          => 'select',
            'class'         => '',
            'label_title'   => __('Select No of projects'),
            'options'       => [
                '6'        => '6',
                '8'        => '8',
            ],
            'default'       => '6',  
            'placeholder'   => __('Select from the list'),  
        ],
        [
            'id'            => 'btn_text',
            'type'          => 'text',
            'value'         => 'Explore more porjects',
            'class'         => '',
            'label_title'   => __('Button heading'),
            'placeholder'   => __('Enter button heading'),
        ],
    ]
];
