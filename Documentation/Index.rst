.. include:: /Includes.rst.txt

======================================
Bilibili Media Support (bilibili_media)
======================================

:Extension key:
   bilibili_media

:Package name:
   malhar-rathod/bilibili-media

:Version:
   1.0.0

:Language:
   en

:Author:
   Malhar Rathod

:Email:
   malhar.b.rathod@gmail.com

:LinkedIn:
   https://www.linkedin.com/in/malhar-b-rathod/

:License:
   This document is published under the Open Publication License available from http://www.opencontent.org/openpub/.

:Rendered:
   |today|

The extension **bilibili_media** enables seamless **Bilibili video embedding** in TYPO3 CMS via the standard **Text & Media** and **Media** content elements (**Add media by URL**).

Compatible with **TYPO3 v12.4 LTS, v13.4 LTS, and v14.x**.

.. contents:: Table of Contents
   :depth: 2
   :local:

What does it do?
================

- **Online Media Provider**: Native FAL integration registered in ``SYS.fal.onlineMediaHelpers.bilibili``.
- **Text & Media & Media CTypes**: Works across ``textmedia`` (assets) and ``media`` (assets/media) content elements.
- **Automatic Thumbnail & Metadata Fetching**: Automatically fetches high-resolution cover artwork from Bilibili API and caches it in FAL storage (``_temp_/online_media/``).
- **Metadata Extraction**: Extracts video title, uploader name, width, and height into FAL metadata fields.
- **Responsive Iframe Player**: Outputs responsive HTML5 player iframe with parameters (``high_quality=1``, ``page=1``, ``referrerpolicy="strict-origin-when-cross-origin"``).
- **Content Security Policy (CSP)**: Ships with CSP mutation rules (``ContentSecurityPolicies.php``) for frontend and backend scopes.


Screenshots
===========

Add Media by URL (Backend)
--------------------------

.. image:: Images/backend_add_media_url.png
   :alt: Add Bilibili Media by URL in TYPO3 Backend Dialog


Imported Media Record & Metadata (Backend)
-------------------------------------------

.. image:: Images/backend_media_record.png
   :alt: Imported Bilibili Media Record in TYPO3 Backend Form Engine


Frontend Video Player Rendering
-------------------------------

.. image:: Images/frontend_bilibili_player.png
   :alt: Bilibili Video Player Rendered in Frontend


Installation
============

Composer Installation (Recommended)
-----------------------------------

Run the following command in your TYPO3 project root:

.. code-block:: bash

   composer require malhar-rathod/bilibili-media


Legacy / Extension Manager Installation
---------------------------------------

1. Download the ZIP package from the TYPO3 Extension Repository (TER).
2. Go to **Admin Tools > Extension Manager** in your TYPO3 Backend.
3. Upload the ZIP file and activate the extension.


Configuration
=============

No extra configuration is required for standard TYPO3 installations.

Site Package / Bootstrap Package Note
-------------------------------------

If your site uses ``bootstrap-package`` or custom media data processors:

1. Go to **Web > Template** in TYPO3 Backend.
2. Edit your root page template record (``sys_template``).
3. Under **Includes > Include static (from extensions)**, select **Bilibili Media Support (bilibili_media)** and save.


Supported URL Formats
=====================

- ``https://www.bilibili.com/video/BV1xx411c7mD``
- ``https://www.bilibili.com/video/BV1xx411c7mD?p=1``
- ``https://b23.tv/BV1xx411c7mD``
- ``https://player.bilibili.com/player.html?bvid=BV1xx411c7mD``
- ``https://www.bilibili.com/video/av12345678``


Content Security Policy (CSP)
=============================

This extension automatically registers CSP mutations in ``Configuration/ContentSecurityPolicies.php``:

- **FrameSrc**: ``*.bilibili.com``, ``player.bilibili.com``
- **ImgSrc**: ``*.hdslb.com``, ``*.bilibili.com``
- **MediaSrc**: ``*.bilivideo.com``, ``*.bilivideo.cn``, ``*.bilibili.com``


Author & Hiring Information
===========================

**Malhar Rathod**
Senior TYPO3 Developer & Web Specialist

- **Email**: `malhar.b.rathod@gmail.com <mailto:malhar.b.rathod@gmail.com>`_
- **LinkedIn**: `https://www.linkedin.com/in/malhar-b-rathod/ <https://www.linkedin.com/in/malhar-b-rathod/>`_
- **Packagist**: `malhar-rathod/bilibili-media <https://packagist.org/packages/malhar-rathod/bilibili-media>`_

available for freelance projects, custom TYPO3 extension development, agency white-label partnerships, upgrades (v11 -> v12 / v13 / v14), and API integrations. Feel free to connect with me.
