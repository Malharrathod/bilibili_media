<?php

declare(strict_types=1);

namespace MalharRathod\BilibiliMedia\Resource\OnlineMedia\Helpers;

use TYPO3\CMS\Core\Resource\Exception\OnlineMediaAlreadyExistsException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\AbstractOnlineMediaHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Bilibili online media helper class
 */
class BilibiliHelper extends AbstractOnlineMediaHelper
{
    /**
     * Get public URL for Bilibili media file
     *
     * @param File $file
     * @return string|null
     */
    public function getPublicUrl(File $file): ?string
    {
        $videoId = $this->getOnlineMediaId($file);
        if (empty($videoId)) {
            return null;
        }

        return sprintf('https://www.bilibili.com/video/%s', rawurlencode($videoId));
    }

    /**
     * Get local absolute file path to preview image
     *
     * @param File $file
     * @return string
     */
    public function getPreviewImage(File $file): string
    {
        $videoId = $this->getOnlineMediaId($file);
        if (empty($videoId)) {
            return '';
        }

        $temporaryFileName = $this->getTempFolderPath() . 'bilibili_' . md5($videoId) . '.jpg';

        if (!file_exists($temporaryFileName)) {
            $apiData = $this->getBilibiliApiData($videoId);
            if (!empty($apiData['data']['pic'])) {
                $thumbnailUrl = $apiData['data']['pic'];
                if (str_starts_with($thumbnailUrl, '//')) {
                    $thumbnailUrl = 'https:' . $thumbnailUrl;
                } elseif (str_starts_with($thumbnailUrl, 'http://')) {
                    $thumbnailUrl = 'https://' . substr($thumbnailUrl, 7);
                }

                $previewImage = GeneralUtility::getUrl(
                    $thumbnailUrl,
                    0,
                    ['User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)']
                );
                if ($previewImage !== false && $previewImage !== '') {
                    GeneralUtility::writeFile($temporaryFileName, $previewImage, true);
                }
            }
        }

        return $temporaryFileName;
    }

    /**
     * Try to transform given URL to a File
     *
     * @param string $url
     * @param Folder $targetFolder
     * @return File|null
     */
    public function transformUrlToFile($url, Folder $targetFolder): ?File
    {
        $videoId = $this->extractVideoIdFromUrl($url);
        if (empty($videoId)) {
            return null;
        }

        return $this->transformMediaIdToFile($videoId, $targetFolder, $this->extension);
    }

    /**
     * Get metadata for Bilibili item
     *
     * @param File $file
     * @return array
     */
    public function getMetaData(File $file): array
    {
        $metadata = [];
        $videoId = $this->getOnlineMediaId($file);
        if (empty($videoId)) {
            return $metadata;
        }

        $apiData = $this->getBilibiliApiData($videoId);
        if (!empty($apiData['data']) && is_array($apiData['data'])) {
            $data = $apiData['data'];
            if (!empty($data['dimension']['width'])) {
                $metadata['width'] = (int)$data['dimension']['width'];
            }
            if (!empty($data['dimension']['height'])) {
                $metadata['height'] = (int)$data['dimension']['height'];
            }
            if (empty($file->getProperty('title')) && !empty($data['title'])) {
                $metadata['title'] = strip_tags((string)$data['title']);
            }
            if (!empty($data['owner']['name'])) {
                $metadata['author'] = (string)$data['owner']['name'];
            }
            if (!empty($data['desc'])) {
                $metadata['description'] = strip_tags((string)$data['desc']);
            }
        }

        return $metadata;
    }

    /**
     * Transform mediaId to File
     *
     * @param string $mediaId
     * @param Folder $targetFolder
     * @param string $fileExtension
     * @return File
     */
    protected function transformMediaIdToFile(string $mediaId, Folder $targetFolder, string $fileExtension): File
    {
        $file = $this->findExistingFileByOnlineMediaId($mediaId, $targetFolder, $fileExtension);
        if ($file !== null) {
            throw new OnlineMediaAlreadyExistsException($file, 1695236851);
        }

        $apiData = $this->getBilibiliApiData($mediaId);
        if (!empty($apiData['data']['title'])) {
            $rawTitle = strip_tags((string)$apiData['data']['title']);
            $cleanTitle = trim(preg_replace('/[\/\\\\?%*:|"<>\0]/u', '_', $rawTitle));
            $fileName = ($cleanTitle !== '' ? $cleanTitle : $mediaId) . '.' . $fileExtension;
        } else {
            $fileName = $mediaId . '.' . $fileExtension;
        }

        return $this->createNewFile($targetFolder, $fileName, $mediaId);
    }

    /**
     * Extract BV or AV video ID from given URL or embed snippet
     *
     * @param string $url
     * @return string|null
     */
    protected function extractVideoIdFromUrl(string $url): ?string
    {
        $url = trim($url);

        // Pattern 1: BV ID (e.g. BV1xx411c7mD)
        if (preg_match('%(?:bilibili\.com/(?:video/|player\.html\?.*bvid=)|b23\.tv/)?(BV[a-zA-Z0-9]{10})%i', $url, $match)) {
            return $match[1];
        }

        // Pattern 2: AV ID (legacy e.g. av12345678)
        if (preg_match('%(?:bilibili\.com/(?:video/|player\.html\?.*aid=)|b23\.tv/)?(av\d+)%i', $url, $match)) {
            return $match[1];
        }

        return null;
    }

    /**
     * Fetch video details from Bilibili API
     *
     * @param string $videoId BV... or av...
     * @return array|null
     */
    protected function getBilibiliApiData(string $videoId): ?array
    {
        $param = str_starts_with(strtolower($videoId), 'bv') ? 'bvid=' . rawurlencode($videoId) : 'aid=' . rawurlencode(substr($videoId, 2));
        $apiUrl = 'https://api.bilibili.com/x/web-interface/view?' . $param;

        $response = GeneralUtility::getUrl(
            $apiUrl,
            0,
            ['User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)']
        );
        if ($response !== false && $response !== '') {
            $data = json_decode($response, true);
            if (is_array($data) && isset($data['code']) && (int)$data['code'] === 0) {
                return $data;
            }
        }

        return null;
    }
}
