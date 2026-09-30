<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Mutation;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationCollection;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationMode;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Scope;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;
use TYPO3\CMS\Core\Type\Map;

if (!class_exists(Scope::class)) {
    return new Map();
}

return Map::fromEntries([
    Scope::frontend(),
    new MutationCollection(
        new Mutation(
            MutationMode::Extend,
            Directive::FrameSrc,
            new UriValue('*.bilibili.com'),
            new UriValue('player.bilibili.com')
        ),
        new Mutation(
            MutationMode::Extend,
            Directive::ImgSrc,
            new UriValue('*.hdslb.com'),
            new UriValue('*.bilibili.com')
        ),
        new Mutation(
            MutationMode::Extend,
            Directive::MediaSrc,
            new UriValue('*.bilivideo.com'),
            new UriValue('*.bilivideo.cn'),
            new UriValue('*.bilibili.com')
        ),
    ),
    Scope::backend(),
    new MutationCollection(
        new Mutation(
            MutationMode::Extend,
            Directive::FrameSrc,
            new UriValue('*.bilibili.com'),
            new UriValue('player.bilibili.com')
        ),
        new Mutation(
            MutationMode::Extend,
            Directive::ImgSrc,
            new UriValue('*.hdslb.com'),
            new UriValue('*.bilibili.com')
        ),
        new Mutation(
            MutationMode::Extend,
            Directive::MediaSrc,
            new UriValue('*.bilivideo.com'),
            new UriValue('*.bilivideo.cn'),
            new UriValue('*.bilibili.com')
        ),
    ),
]);
