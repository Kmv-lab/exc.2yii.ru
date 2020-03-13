<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dbResort = require __DIR__ . '/dbResort.php';

//Какой-то комент всем плевать

//Но не мне нах ёпт

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\controllers\Settings',//класс добавляет к настройкам параметры из бд
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru-RU',
    'modules' => [
        'adm' => [
            'class' => 'app\modules\adm\Module',
        ],
    ],

    'timeZone' => 'Europe/Moscow',
    'components' => [
        'request' => [
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => md5('hrhsdf;lo894jj;'),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\adm\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
               // 'extraParams' => null,
                'host' => '',
                'username' => '',
                'password' => '',
                'port' => '465',
                'encryption' => 'ssl',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'dbResort' => $dbResort,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'enableStrictParsing' => false,
            'rules' => [
                /**
                 * 'countries/<direction_alias:\S+>/hotels-<stars:\d+>' => 'directions/hotels',
                 *
                \d    Соответствует любой цифре; эквивалент класса [0-9].
                \D    Соответствует любому нечисловому символу; эквивалент класса [^0-9].
                \s    Соответствует любому символу whitespace; эквивалент [ \t\n\r\f\v].
                \S    Соответствует любому не-whitespace символу; эквивалент [^ \t\n\r\f\v].
                \w    Соответствует любой букве или цифре; эквивалент [a-zA-Z0-9_].
                \W    Наоборот; эквивалент [^a-zA-Z0-9_]
                 */
                'debug/default/toolbar'                 => 'debug/default/toolbar',
                'debug/default/view'                    => 'debug/default/view',//костыл, для работы дебага с функционалом страниц
                'debug/default/db-explain'              => 'debug/default/db-explain',

                //стр будущего сайта
                '/' => 'site/index',
                'prices' => 'site/prices',
                //'dnd' => 'site/dnd',
                'sanatorium/<alias:\S+>' => 'site/sanatorium',
                'o-nas/news' => 'news/news',
                'o-nas/news/<alias:\S+>' => 'news/new',
                'o-nas/blog' => 'news/blogs',
                'o-nas/blog/<alias:\S+>' => 'news/blog',
                'o-nas/articles' => 'news/articles',
                'o-nas/articles/<alias:\S+>' => 'news/article',
                'o-nas/testi' => 'site/reviews',
                //'services/<alias:\S+>' => 'services/detail',
                //'services' => 'services/index',
                'contacts' => 'site/contacts',
                'site/contactform' => 'site/contactform',//ошибка страницы
                'site/sendphone' => 'site/sendphone',//пустая страница
                'style' => 'site/style',
                //'spec/<page:\d+>' => 'site/spec',
                'spec' => 'site/spec',
                'reviews' => 'site/reviews2',
                'ver' => 'pages/ver',
                [
                    'pattern' => 'sitemap\Sxml' ,
                    'route' => 'site/sitemap',
                    'suffix' => ''
                ],


                'adm/<controller:\w+>/<action:\w+>' =>  'adm/<controller>/<action>',
                'adm' => 'adm/default/index',


                '<alias:\S+>'               => 'site/page', //страницы
            ],
        ],
        'session'=>[
            'class' => 'yii\web\Session',
            'cookieParams' => ['lifetime' => 14*24*60*60]
        ],

    ],
    'params' => $params,
];

if (true) {//if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;
