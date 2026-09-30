<?php

declare(strict_types=1);

namespace MalharRathod\BilibiliMedia\Resource\Rendering;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Bilibili media renderer class
 */
class BilibiliRenderer implements FileRendererInterface
{
    /**
     * Priority of the renderer (higher numbers take precedence over fallback renderers)
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 10;
    }

    /**
     * Check if given File or FileReference can be rendered by BilibiliRenderer
     *
     * @param FileInterface $file
     * @return bool
     */
    public function canRender(FileInterface $file): bool
    {
        $orgFile = $this->getOriginalFile($file);
        if (!$orgFile instanceof File) {
            return false;
        }

        $extension = strtolower($orgFile->getExtension());
        $mimeType = strtolower($orgFile->getMimeType());

        return $extension === 'bilibili' || $mimeType === 'video/bilibili' || str_contains($mimeType, 'bilibili');
    }

    /**
     * Render HTML iframe for Bilibili media
     *
     * @param FileInterface $file
     * @param int|string $width
     * @param int|string $height
     * @param array $options
     * @return string
     */
    public function render(FileInterface $file, $width, $height, array $options = []): string
    {
        $options = $this->collectOptions($options, $file);
        $src = $this->createBilibiliUrl($options, $file);

        if (empty($src)) {
            return '';
        }

        $attributes = $this->collectIframeAttributes($width, $height, $options);

        return sprintf(
            '<iframe %s="%s"%s></iframe>',
            $options['srcAttribute'] ?? 'src',
            htmlspecialchars($src, ENT_QUOTES | ENT_HTML5),
            empty($attributes) ? '' : ' ' . $this->implodeAttributes($attributes)
        );
    }

    /**
     * Get Online Media Helper instance
     *
     * @param FileInterface $file
     * @return false|OnlineMediaHelperInterface
     */
    protected function getOnlineMediaHelper(FileInterface $file)
    {
        $orgFile = $this->getOriginalFile($file);
        if ($orgFile instanceof File) {
            return GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class)
                ->getOnlineMediaHelper($orgFile);
        }

        return false;
    }

    /**
     * Helper to retrieve underlying File object from FileReference or ProcessedFile
     *
     * @param FileInterface $file
     * @return FileInterface
     */
    protected function getOriginalFile(FileInterface $file): FileInterface
    {
        $orgFile = $file;
        while ($orgFile instanceof FileReference || $orgFile instanceof ProcessedFile) {
            $orgFile = $orgFile->getOriginalFile();
        }
        return $orgFile;
    }

    /**
     * Collect renderer options
     *
     * @param array $options
     * @param FileInterface $file
     * @return array
     */
    protected function collectOptions(array $options, FileInterface $file): array
    {
        if (!isset($options['autoplay']) && $file instanceof FileReference) {
            $autoplay = $file->getProperty('autoplay');
            if ($autoplay !== null) {
                $options['autoplay'] = $autoplay;
            }
        }

        if (!isset($options['allow'])) {
            $options['allow'] = 'fullscreen';
            if (!empty($options['autoplay'])) {
                $options['allow'] = 'autoplay; fullscreen';
            }
        }

        return $options;
    }

    /**
     * Create Bilibili player embed URL
     *
     * @param array $options
     * @param FileInterface $file
     * @return string
     */
    protected function createBilibiliUrl(array $options, FileInterface $file): string
    {
        $videoId = $this->getVideoIdFromFile($file);
        if (empty($videoId)) {
            return '';
        }

        $urlParams = [];
        if (str_starts_with(strtolower($videoId), 'bv')) {
            $urlParams[] = 'bvid=' . rawurlencode($videoId);
        } else {
            $urlParams[] = 'aid=' . rawurlencode(substr($videoId, 2));
        }

        $urlParams[] = 'page=1';
        $urlParams[] = 'high_quality=1';
        $urlParams[] = 'danmaku=' . (!empty($options['danmaku']) ? '1' : '0');
        $urlParams[] = 'autoplay=' . (!empty($options['autoplay']) ? '1' : '0');

        return 'https://player.bilibili.com/player.html?' . implode('&', $urlParams);
    }

    /**
     * Extract video ID from file
     *
     * @param FileInterface $file
     * @return string
     */
    protected function getVideoIdFromFile(FileInterface $file): string
    {
        $orgFile = $this->getOriginalFile($file);
        $helper = $this->getOnlineMediaHelper($orgFile);

        return $helper ? $helper->getOnlineMediaId($orgFile) : '';
    }

    /**
     * Collect attributes for iframe tag
     *
     * @param int|string $width
     * @param int|string $height
     * @param array $options
     * @return array
     */
    protected function collectIframeAttributes($width, $height, array $options): array
    {
        $attributes = [];
        $attributes['allowfullscreen'] = true;
        $attributes['frameborder'] = 0;
        $attributes['referrerpolicy'] = 'strict-origin-when-cross-origin';

        if (isset($options['additionalAttributes']) && is_array($options['additionalAttributes'])) {
            $attributes = array_merge($attributes, $options['additionalAttributes']);
        }

        if ((int)$width > 0) {
            $attributes['width'] = (int)$width;
        }
        if ((int)$height > 0) {
            $attributes['height'] = (int)$height;
        }

        foreach (['class', 'dir', 'id', 'lang', 'style', 'title', 'allow'] as $key) {
            if (!empty($options[$key])) {
                $attributes[$key] = $options[$key];
            }
        }

        return $attributes;
    }

    /**
     * Implode array of attributes into HTML string
     *
     * @param array $attributes
     * @return string
     */
    protected function implodeAttributes(array $attributes): string
    {
        $attributeList = [];
        foreach ($attributes as $name => $value) {
            $name = preg_replace('/[^\p{L}0-9_.-]/u', '', $name);
            if ($value === true) {
                $attributeList[] = $name;
            } else {
                $attributeList[] = $name . '="' . htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5) . '"';
            }
        }

        return implode(' ', $attributeList);
    }
}
