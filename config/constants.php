<?php

/**
 * All user-defined constants placed here
 *
 * @package config
 *
 * @file constants.php
 *
 * @author Azim Khan <azim@surmountsoft.in>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

$constants = [
    'STRIPE_KEYS' => [
        'test' => [
            "secret_key" => "sk_test_MR36jksa6EXJVDp18pK52Gdt",
            "publishable_key" => "pk_test_qQi5J8BWugzyiNE1Ma39FgET",
        ],
        'live' => [
            "secret_key" => "sk_live_39SGWvp20zkl598PdArVoEOp",
            "publishable_key" => "pk_live_DZZ1Fo7wQfSjptPItuQz1ufu",
        ]
    ],
    'STRIPE_CURRENCY' => 'usd',
    'STRIPE_VERSION' => '2018-01-23',
    'FOOTER_CONTENTS' => [
        1 => 'About',
        2 => 'Help',
        3 => 'Privacy Policy',
        4 => 'Terms & Conditions',
    ],
    'SOCIAL_PLATFORMS' => [
        1 => 'Twitter',
        2 => 'Instagram'
    ],
    'REPORT_LIST' => [
        1 => 'Abusive Language',
        2 => 'Inappropriate Content',
        3 => 'Quality Issues',
        4 => 'Fake Profile',
        5 => 'Duplicate Profile',
    ],
    'REJECTED_REASONS' => [
        1 => 'Not Available',
        2 => 'Not Interested',
        3 => 'Other',
    ],
    'BCC_MAIL_CONFIG'=>[
        'admin_email'   => (env('APP_ENV') == 'local' || env('APP_ENV') == 'testing')?'staging@videorequestline.com':'admin@videorequestline.com',
        'admin_email_display_name' =>'VRL Admin'
    ],
    'EXCEPTION_OCCURS'=>[
        'exception_email'=>[
            'support@videorequestline.com',
        ],
        'exception_name' =>'Error log'
    ],
];

if (env('APP_ENV') == 'local' || env('APP_ENV') == 'testing') {
    $constants['EXCEPTION_OCCURS']['exception_email'] = ['jaspreet.singh@surmountsoft.com', 'ritu.slaria@surmountsoft.com'];
} else {
    // Production
    $constants['EXCEPTION_OCCURS']['exception_email'] = ['sudhir.singh@surmountsoft.com', 'jaspreet.singh@surmountsoft.com'];
}

return $constants; 
