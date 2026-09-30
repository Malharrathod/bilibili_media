<?php

defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'bilibili_media',
    'Configuration/TypoScript',
    'Bilibili Media Support'
);
