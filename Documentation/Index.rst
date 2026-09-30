.. include:: /Includes.rst.txt

======================
Bilibili Media Support
======================

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

The extension **bilibili_media** enables Bilibili video embedding in TYPO3 CMS via the standard **Text & Media** content element (Online Media / Add media by URL).

Compatible with **TYPO3 v12, v13, and v14**.

What does it do?
================

- Enables pasting Bilibili video URLs into "Add media by URL" in Text & Media elements.
- Automatically fetches video cover image thumbnails via Bilibili API and caches them in FAL storage.
- Automatically extracts title, uploader name, width, and height.
- Renders responsive iframe embeds using `player.bilibili.com`.
- Ships with Content Security Policy (CSP) mutations for frontend and backend scopes.


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

Composer Installation
---------------------

In your TYPO3 project root directory, run:

.. code-block:: bash

   composer require malhar-rathod/bilibili-media


Configuration
=============

No extra configuration is required! Once activated, Bilibili URLs are automatically supported in all media fields that allow Online Media.

Supported URL Formats
---------------------

- ``https://www.bilibili.com/video/BV1xx411c7mD``
- ``https://www.bilibili.com/video/BV1xx411c7mD?p=1``
- ``https://b23.tv/BV1xx411c7mD``
- ``https://player.bilibili.com/player.html?bvid=BV1xx411c7mD``
- ``https://www.bilibili.com/video/av12345678``
