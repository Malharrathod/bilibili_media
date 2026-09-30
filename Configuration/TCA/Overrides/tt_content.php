<?php

defined('TYPO3') or die();

(static function () {
    /**
     * Helper to append 'bilibili' to allowed file extension settings in TCA
     */
    $addBilibiliToConfig = static function (array &$config): void {
        // Handle 'allowed' configuration as string
        if (isset($config['allowed']) && is_string($config['allowed']) && $config['allowed'] !== '') {
            if (!\TYPO3\CMS\Core\Utility\GeneralUtility::inList($config['allowed'], 'bilibili')) {
                $config['allowed'] .= ',bilibili';
            }
        } elseif (isset($config['allowed']) && is_array($config['allowed'])) {
            if (!in_array('bilibili', $config['allowed'], true)) {
                $config['allowed'][] = 'bilibili';
            }
        }

        // Handle 'appearance.allowedFileExtensions' if set
        if (isset($config['appearance']['allowedFileExtensions']) && is_string($config['appearance']['allowedFileExtensions']) && $config['appearance']['allowedFileExtensions'] !== '') {
            if (!\TYPO3\CMS\Core\Utility\GeneralUtility::inList($config['appearance']['allowedFileExtensions'], 'bilibili')) {
                $config['appearance']['allowedFileExtensions'] .= ',bilibili';
            }
        }
    };

    // 1. Process all columns in tt_content where type === 'file' or field name relates to media
    if (isset($GLOBALS['TCA']['tt_content']['columns']) && is_array($GLOBALS['TCA']['tt_content']['columns'])) {
        foreach ($GLOBALS['TCA']['tt_content']['columns'] as $fieldName => $fieldConfig) {
            if (isset($fieldConfig['config']) && is_array($fieldConfig['config'])) {
                if (($fieldConfig['config']['type'] ?? '') === 'file' || in_array($fieldName, ['assets', 'media', 'image', 'multimedia', 'files'], true)) {
                    $addBilibiliToConfig($GLOBALS['TCA']['tt_content']['columns'][$fieldName]['config']);
                }
            }
        }
    }

    // 2. Process type-specific columnsOverrides across all tt_content CTypes
    if (isset($GLOBALS['TCA']['tt_content']['types']) && is_array($GLOBALS['TCA']['tt_content']['types'])) {
        foreach ($GLOBALS['TCA']['tt_content']['types'] as $cType => $typeConfig) {
            if (isset($typeConfig['columnsOverrides']) && is_array($typeConfig['columnsOverrides'])) {
                foreach ($typeConfig['columnsOverrides'] as $fieldName => $fieldOverride) {
                    if (isset($fieldOverride['config']) && is_array($fieldOverride['config'])) {
                        $addBilibiliToConfig($GLOBALS['TCA']['tt_content']['types'][$cType]['columnsOverrides'][$fieldName]['config']);
                    }
                }
            }
        }
    }
})();
