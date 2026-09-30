<?php

defined('TYPO3') or die();

(static function () {
    // 1. Add 'bilibili' extension to SYS mediafile_ext list if not present
    $mediaFileExt = $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] ?? '';
    if (!\TYPO3\CMS\Core\Utility\GeneralUtility::inList($mediaFileExt, 'bilibili')) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] = ltrim($mediaFileExt . ',bilibili', ',');
    }

    // 2. Register Bilibili Online Media Helper
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['bilibili'] =
        \MalharRathod\BilibiliMedia\Resource\OnlineMedia\Helpers\BilibiliHelper::class;

    // 3. Register Bilibili File Renderer
    $rendererRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::class
    );
    $rendererRegistry->registerRendererClass(
        \MalharRathod\BilibiliMedia\Resource\Rendering\BilibiliRenderer::class
    );

    // 4. Register file extension to MIME type mapping & compatibility
    // Crucial for TYPO3 FAL to detect .bilibili files as video/bilibili (FileType::VIDEO = 4)
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['bilibili'] = 'video/bilibili';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['mimeTypeCompatibility']['text/plain']['bilibili'] = 'video/bilibili';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['mimeTypeCompatibility']['application/octet-stream']['bilibili'] = 'video/bilibili';

    // 5. Append bilibili to DataProcessors (e.g. bootstrap-package FileFilterProcessor for tt_content.media)
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'bilibili_media',
        'setup',
        '
        tt_content.media.dataProcessing.20.allowedFileExtensions := addToList(bilibili)
        ',
        'default'
    );
})();
