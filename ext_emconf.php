<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Bilibili Media Support for Text & Media',
    'description' => 'Adds Bilibili video URL support to TYPO3 Online Media in Text & Media elements.',
    'category' => 'fe',
    'author' => 'Team NorthbridgeNet',
    'author_email' => 'info@northbridgenet.com',
    'author_company' => 'NorthbridgeNet',
    'state' => 'stable',
    'version' => '1.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
