<?php

$path = $_SERVER['DOCUMENT_ROOT'];
return [
    //'adminEmail' => 'admin@example.com',
    'resolution_main_sanatorium_photo' => '500x300',

    'monhts_to_russian'             => [
                                        'Января',
                                        'Февраля',
                                        'Марта',
                                        'Апреля',
                                        'Мая',
                                        'Июня',
                                        'Июля',
                                        'Августа',
                                        'Сентабря',
                                        'Октября',
                                        'Ноября',
                                        'Декабря'
                                    ],

    'cityes'                        => [
                                        'Пятигорск',
                                        'Ессентуки',
                                        'Кисловодск',
                                        'Железноводск'
                                    ],

  'type_san_block'                  => [
                                        'Профили лечения',//0
                                        'Галерея',//1
                                        'WYSIWYG',
                                        'Code-Mirror',//3
                                        'YouTube',
                                        'Номера',//5
                                        'Цены'
                                    ],

    'min_image_size_for_upload'       => 100,
    'max_image_size_for_upload'       => 100000000000,

    'full_path_to_galleries_images'        =>  $path.'/content/galleries/',
    'path_to_galleries_images'             =>  '/content/galleries/',

    'full_path_to_sanatoriums_galleries_images'        =>  $path.'/content/sanGalleries/',
    'path_to_sanatoriums_galleries_images'             =>  '/content/sanGalleries/',

    'full_path_to_sanatoriums_photo'        =>  $path.'/content/images/sans/',
    'path_to_sanatoriums_photo'             =>  '/content/images/sans/',


    'full_path_to_sliders_images'          =>  $path.'/content/sliders/',
    'path_to_sliders_images'               =>  '/content/sliders/',


    'full_path_to_pages_images'             =>  $path.'/content/pages/',
    'path_to_pages_images'                  =>  '/content/pages/',


    'full_path_to_news_images'             =>  $path.'/content/news/',
    'path_to_news_images'                  =>  '/content/news/',

    'full_path_to_main_page_images'             =>  $path.'/content/mainPage/',
    'path_to_main_page_images'                  =>  '/content/mainPage/',

    'full_path_to_actions_images'             =>  $path.'/content/actions/',
    'path_to_actions_images'                  =>  '/content/actions/',

    'full_path_to_spec_images'             =>  $path.'/content/spec/',
    'path_to_spec_images'                  =>  '/content/spec/',

    'path_to_official_images'               => '/img/',


    'second_menu'                       => [],

    'resolutions_folders'               =>  ['galleries', 'sliders', 'news', 'spec', 'actions'],
    'resolutions_parent_folders'        =>  'content',
    'path'                              => $path,

    'seo_h1'                            => '',

    'seo_h1_span'                       => '',

    'counter_form'                      => 0,

    'photo'                             => '',

];
