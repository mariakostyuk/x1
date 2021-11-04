<?php
date_default_timezone_set("Europe/Minsk");
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'timeZone' => date_default_timezone_get(),
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
    	'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
	    'enableSwiftMailerLogging' => true,
            'useFileTransport' => true,
            'transport' => [
//		'class' => 'Swift_SendmailTransport',
            ]


            	],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        	],
	'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV2' => '',
            'secretV2' => '',
            'siteKeyV3' => '',
            'secretV3' => '',
    		],

	],

];
