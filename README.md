# TYPO3 Bilibili Media Extension (`bilibili_media`)

[![TYPO3 v12](https://img.shields.io/badge/TYPO3-v12.4-orange.svg)](https://typo3.org)
[![TYPO3 v13](https://img.shields.io/badge/TYPO3-v13.4-orange.svg)](https://typo3.org)
[![TYPO3 v14](https://img.shields.io/badge/TYPO3-v14.0-orange.svg)](https://typo3.org)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](LICENSE)

An extension for TYPO3 CMS that enables **Bilibili video integration** within the standard **Text & Media** content element via **Online Media (Add media by URL)**.

Supports **TYPO3 v12, v13, and v14**.

---

## Features

- **Seamless Bilibili Integration**: Paste Bilibili video URLs directly into the "Add media by URL" dialog in Text & Media (`assets`) and Media (`media`) content elements.
- **Automatic Thumbnail Fetching**: Fetches high-resolution video cover images directly from Bilibili's API and caches them in TYPO3's FAL online media assets directory.
- **Metadata Extraction**: Automatically extracts video title, author/uploader, width, and height.
- **Responsive Iframe Rendering**: Generates clean, responsive HTML5 `<iframe>` embeds targeting Bilibili's player (`player.bilibili.com`).
- **Content Security Policy (CSP)**: Ships with ready-to-use CSP rules for TYPO3 frontend and backend.
- **Backend SVG Icon**: Includes custom Bilibili SVG mime-type icon for Filelist and Form Engine.

---

## Screenshots

### 1. Add Media by URL Modal (Backend)
![Add Bilibili Media by URL](Documentation/Images/backend_add_media_url.png)

### 2. Imported Media Record & Metadata (Backend)
![Backend Media Record View](Documentation/Images/backend_media_record.png)

### 3. Responsive Bilibili Video Player (Frontend)
![Frontend Bilibili Video Player](Documentation/Images/frontend_bilibili_player.png)

---

## Supported URL Formats

The extension automatically recognizes and extracts BV/AV identifiers from the following Bilibili URL formats:

- Standard video URL: `https://www.bilibili.com/video/BV1xx411c7mD`
- Video URL with parameters: `https://www.bilibili.com/video/BV1xx411c7mD?p=1`
- Shortened URLs: `https://b23.tv/BV1xx411c7mD`
- Bilibili Player URLs: `https://player.bilibili.com/player.html?bvid=BV1xx411c7mD`
- Legacy AV URLs: `https://www.bilibili.com/video/av12345678`
- Full `<iframe>` HTML embed snippets containing Bilibili video URLs.

---

## Requirements

- **PHP**: ^8.1 || ^8.2 || ^8.3 || ^8.4
- **TYPO3 CMS**: ^12.4 || ^13.4 || ^14.0

---

## Installation

### Installation via Composer (Recommended)

Run the following command in your TYPO3 project root:

```bash
composer require malhar-rathod/bilibili-media
```

### Installation via TYPO3 Extension Manager (Legacy mode)

1. Download the extension ZIP file from TER (TYPO3 Extension Repository).
2. Open TYPO3 Backend and go to **Admin Tools > Extensions**.
3. Upload the extension `.zip` file and click **Activate**.

---

## Usage Guide

1. Log into the **TYPO3 Backend**.
2. Navigate to the **Page** module and edit or create a **Text & Media** content element (or any FAL media field).
3. Switch to the **Media** tab.
4. Click **Add media by URL**.
5. Paste any valid Bilibili video link (e.g., `https://www.bilibili.com/video/BV1xx411c7mD`) and press **Add**.
6. The video file will be added as a `.bilibili` FAL file record with thumbnail and title automatically populated.
7. Save the content element and view it on the Frontend!

---

## Content Security Policy (CSP)

This extension automatically registers mutations in `Configuration/ContentSecurityPolicies.php` for `Scope::frontend()` and `Scope::backend()`:

- **FrameSrc**: `*.bilibili.com`, `player.bilibili.com`
- **ImgSrc**: `*.hdslb.com`, `*.bilibili.com`

---

## Author

**Malhar Rathod**
- **Email**: [malhar.b.rathod@gmail.com](mailto:malhar.b.rathod@gmail.com)
- **LinkedIn**: [https://www.linkedin.com/in/malhar-b-rathod/](https://www.linkedin.com/in/malhar-b-rathod/)
- **Package**: `malhar-rathod/bilibili-media`
- **Extension Key**: `bilibili_media`
