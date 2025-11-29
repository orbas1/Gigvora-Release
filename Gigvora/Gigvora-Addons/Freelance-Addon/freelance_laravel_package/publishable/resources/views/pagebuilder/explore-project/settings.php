<?php

return [
    'id' => 'explore-project',
    'name' => __('Explore project'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [      
            [
                'id'            => 'explore_project_verient',
                'type'          => 'select',
                'class'         => '',
                'label_title'   => __('Select verient'),
                'options'       => [
                    'style1'        => 'Style 1',
                    'style2'        => 'Style 2',
                ],
                'default'       => '',  
                'placeholder'   => __('Select from the list'),  
            ],    
            [
                'id'            => 'bg_color',
                'type'          => 'colorpicker',
                'value'         => '',
                'class'         => '',
                'label_title'   => __('Background color'),
            ],                                                
            [
                'id'            => 'border_color',
                'type'          => 'colorpicker',
                'value'         => '',
                'class'         => '',
                'label_title'   => __('Border color'),
            ],                                                
            [
                'id'            => 'sub-heading',
                'type'          => 'text',
                'value'         => 'Explore the talent pool',
                'class'         => '',
                'label_title'   => __('Sub heading'),
                'placeholder'   => __('Enter sub heading'),
            ],
            [
                'id'            => 'heading',
                'type'          => 'text',
                'value'         => 'Post a New Project',
                'class'         => '',
                'label_title'   => __('Heading'),
                'placeholder'   => __('Enter heading'),
            ],
            [
                'id'            => 'paragraph',
                'type'          => 'text',
                'value'         => 'Give a boost to your project with the hand picked and verified talent.',
                'class'         => '',
                'label_title'   => __('Paragraph'),
                'placeholder'   => __('Enter paragraph'),
            ],
            [
                'id'            => 'image',
                'type'          => 'file',
                'class'         => '',
                'label_title'   => __('Images'),
                'label_desc'    => __('Add images'),
                'max_size'   => 4,                  // size in MB
                'ext'    =>[
                    'jpg',
                    'png',
                    'svg',
                ], 
            ],
            [
                'id'            => 'btn_url',
                'type'          => 'text',
                'value'         => "#",
                'class'         => '',
                'label_title'   => __('Button url'),
                'placeholder'   => __('Enter url'),
            ],
            [
                'id'            => 'btn_text',
                'type'          => 'text',
                'value'         => "",
                'class'         => '',
                'label_title'   => __('Button url'),
                'placeholder'   => __('Enter url'),
            ],
        ]
];
