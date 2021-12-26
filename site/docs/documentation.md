---
eleventyNavigation:
  key: Documentation
  url: '/documentation/index.html'
  order: 3
permalink: documentation/index.html
layout: base.njk
title: Documentation
description: True WYSIWYG for Joomla's tinyMCE Editor Documentation
---

# Installation

{% assign fff = downloads | first %}

For the installation the procedure is the expected one and once the package is installed the fuctionality is immediately available. Here are the two different ways to install the package:
- Using drag and drop
  - Download the package [{{fff.version}}]({{ metainfo.url }}/dist/{{fff.name}})
  - Login to your site's backend and go to system from the menu {% image "./site/images/install_1.png", "System Dashboard", "(min-width: 30em) 50vw, 100vw" %}

  - Click on the link `Extensions` in the `Install` card. The new page should have the tab `Upload Package File` selected, if not click that tab.   {% image "./site/images/install_2.png" "Drag and drop installation", "Drag and drop installation", "(min-width: 30em) 50vw, 100vw" %}

  - Drag and drop the file in the dropdown area. Done!
- Using a link
  - Login to your site's backend and go to system
  - Click on the link `Extensions` in the `Install` card
  - On the new page click on the tab `Install from URL`. {% image "./site/images/install_3.png" "Drag and drop installation", "Install from URL", "(min-width: 30em) 50vw, 100vw" %}
  - Paste the link: 
    `{{ metainfo.url }}/dist/{{fff.name}}`
    and click the button Check and Install. Done.

## Enabling WYSIWYG for templates other than Cassiopeia
The plugin although will be enabled for all templates, only the `Cassiopeia` template will have the needed files. If you are using another template you will need to add the following files to the template:
- a file called `jeditor.php` in the `templates/yourTemplateName` folder
- a file called `style_formats.json` in the `media/templates/site/yourTemplateName/js` folder
- a file called `style.json` in the `media/templates/site/yourTemplateName/js` folder
- Sample of the contents of these files are available below

<details>
<summary>sample implementation of jeditor.php</summary>

### The purpose of the file is to return a response of the pure CSS of the template

```php
<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.cassy
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

/**
 * This endpoint returns the CSS content for the template
 */
$wa   = $this->getWebAssetManager();
$mv   = $this->getMediaVersion();

$this->setMimeEncoding('text/css');
$this->setCharset('utf-8');

// Template assets path
$templatePath = 'media/templates/site/' . $this->template;

// Color Theme
$paramsColorName = $this->params->get('colorName', 'colors_standard');
$assetColorName  = 'theme.' . $paramsColorName;

// Use a font scheme if set in the template style options
$paramsFontScheme = $this->params->get('useFontScheme', false);
$fontStyles       = '';

$wa->registerAndUseStyle($assetColorName, $templatePath . '/css/global/' . $paramsColorName . '.css');

if ($paramsFontScheme) {
  if (stripos($paramsFontScheme, 'https://') === 0) {
    $this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', []);
    $this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', []);
    $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], []);

    if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0) {
      $fontStyles = '--cassy-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
  --cassy-font-family-headings: "' . str_replace('+', ' ', isset($matches[1][1]) ? $matches[1][1] : $matches[1][0]) . '", sans-serif;
  --cassy-font-weight-normal: 400;
  --cassy-font-weight-headings: 700;';
    }
  } else {
    $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], []);
  }
}

// Enable assets
$wa->useStyle('template.cassy.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
  ->useStyle('template.active.language')
  ->useStyle('template.user');

// Get the URLs
$templateCSSUri       = $wa->getAsset('style', 'template.cassy.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))->getUri();
$activeLanguageCSSUri = $wa->getAsset('style', 'template.active.language')->getUri();
$userCSSUri           = $wa->getAsset('style', 'template.user')->getUri();
$fontsCSSUri          = $wa->assetExists('style', 'fontscheme.current') ? $wa->getAsset('style', 'fontscheme.current')->getUri() : '';

// Assign the URLs to CSS imports
$templateCSS       = ($templateCSSUri !== '') ? '@import url("' . Uri::root() . substr($templateCSSUri, 1) . '?' . $mv . '");' : '';
$activeLanguageCSS = ($activeLanguageCSSUri !== '') ? '@import url("' . Uri::root() . substr($activeLanguageCSSUri, 1) . '?' . $mv . '");' : '';
$userCSS           = ($userCSSUri !== '') ? '@import url("' . Uri::root() .  substr($userCSSUri, 1) . '?' . $mv . '");' : '';
$fontsCSS          = ($fontsCSSUri !== '') ? '@import url("' .  $fontsCSSUri . '");' : '';

echo <<<CSS
@charset "UTF-8";
/** Template CSS */
$templateCSS

/** Active Language CSS */
$activeLanguageCSS

/** User CSS */
$userCSS

/** Fonts CSS */
$fontsCSS

/** Inline */
:root {
  --hue: 214;
  --template-bg-light: #f0f4fb;
  --template-text-dark: #495057;
  --template-text-light: #ffffff;
  --template-link-color: #2a69b8;
  --template-special-color: #001B4C;
  $fontStyles
}

/* STYLES FOR JOOMLA! EDITOR */
hr#system-readmore {
  color: #f00;
  border: #f00 dashed 1px;
}
CSS;
```

</details>

<details>
<summary>style_formats.json</summary>

### The purpose of this file is to assign Format names to specific format classes (the file below).

Check the [tinyMCE docs](https://www.tiny.cloud/docs/configure/editor-appearance/#style_formats) for further details.

```json
{
  "0": {
    "title": "Headers",
    "items": {
      "0": {
        "title": "Header 1",
        "format": "h1"
      },
      "1": {
        "title": "Header 2",
        "format": "h2"
      },
      "2": {
        "title": "Header 3",
        "format": "h3"
      },
      "3": {
        "title": "Header 4",
        "format": "h4"
      },
      "4": {
        "title": "Header 5",
        "format": "h5"
      },
      "5": {
        "title": "Header 6",
        "format": "h6"
      }
    }
  },
  "1": {
    "title": "Inline",
    "items": {
      "0": {
        "title": "Bold",
        "icon": "bold",
        "format": "bold"
      },
      "1": {
        "title": "Italic",
        "icon": "italic",
        "format": "italic"
      },
      "2": {
        "title": "Underline",
        "icon": "underline",
        "format": "underline"
      },
      "3": {
        "title": "Strikethrough",
        "icon": "strikethrough",
        "format": "strikethrough"
      },
      "4": {
        "title": "Superscript",
        "icon": "superscript",
        "format": "superscript"
      },
      "5": {
        "title": "Subscript",
        "icon": "subscript",
        "format": "subscript"
      },
      "6": {
        "title": "Code",
        "icon": "code",
        "format": "code"
      }
    }
  },
  "2": {
    "title": "Blocks",
    "items": {
      "0": {
        "title": "paragraph",
        "format": "p"
      },
      "1": {
        "title": "Blockquote",
        "format": "blockquote"
      },
      "2": {
        "title": "Div",
        "format": "div"
      },
      "3": {
        "title": "pre",
        "format": "pre"
      }
    }
  },
  "3": {
    "title": "Alignment",
    "items": {
      "0": {
        "title": "Left",
        "icon": "alignleft",
        "format": "alignleft"
      },
      "1": {
        "title": "Center",
        "icon": "aligncenter",
        "format": "aligncenter"
      },
      "2": {
        "title": "Right",
        "icon": "alignright",
        "format": "alignright"
      },
      "3": {
        "title": "Justify",
        "icon": "alignjustify",
        "format": "alignjustify"
      }
    }
  }
}
```

</details>

<details>
<summary>formats.json</summary>

### The purpose of this file is to assign CSS classes (or inline CSS) to specific formats (the file above).

Check the [tinyMCE docs](https://www.tiny.cloud/docs/configure/content-formatting/#formats) for further details.

```json
{
  "alignleft": {
    "selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img",
    "classes": "text-start"
  },
  "aligncenter": {
    "selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img",
    "classes": "text-center"
  },
  "alignright": {
    "selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img",
    "classes": "text-end"
  },
  "alignjustify": {
    "selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img",
    "classes": "text-justify"
  },
  "bold": {
    "inline": "span",
    "classes": "fw-bold"
  },
  "italic": {
    "inline": "span",
    "classes": "fst-italic"
  },
  "underline": {
    "inline": "span",
    "classes": "text-decoration-underline",
    "exact": "true"
  },
  "strikethrough": {
    "inline": "del",
    "classes": "text-decoration-line-through"
  }
}
```

</details>
