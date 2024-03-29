<?php
/**
 * @copyright   (C) 2021 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * This endpoint returns ONLY the CSS contents for the template
 */

 defined('_JEXEC') || die;

/** @var Joomla\CMS\Document\HtmlDocument $this */
$this->setMimeEncoding('text/css');
$this->setCharset('utf-8');

$wa = $this->getWebAssetManager();
$mv = $this->getMediaVersion();
$paramsFontScheme = $this->params->get('useFontScheme', false); // Use a font scheme if set in the template style options
$paramsColorName  = $this->params->get('colorName', 'colors_standard'); // Color Theme

if ($paramsFontScheme) {
  if (stripos($paramsFontScheme, 'https://') === 0) {
    $this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', []);
    $this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', []);
    $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], []);

    if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0) {
      $fontStyles = '--cassiopeia-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
  --cassiopeia-font-family-headings: "' . str_replace('+', ' ', isset($matches[1][1]) ? $matches[1][1] : $matches[1][0]) . '", sans-serif;
  --cassiopeia-font-weight-normal: 400;
  --cassiopeia-font-weight-headings: 700;';
    }
  } else {
    $fontStyles = '';
    $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], []);
  }
} else {
  $fontStyles = '';
}

$wa->registerAndUseStyle('theme.' . $paramsColorName, 'global/' . $paramsColorName . '.css')
  ->useStyle('template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
  ->useStyle('template.active.language')
  ->registerAndUseStyle('j-editor-css', 'editor.css', [], [])
  ->useStyle('template.user');

// Get the URLs
$templateCSSUri       = $wa->getAsset('style', 'template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))->getUri();
$templateColorsUri    = $wa->getAsset('style', 'theme.' . $paramsColorName)->getUri();
$activeLanguageCSSUri = $wa->getAsset('style', 'template.active.language')->getUri();
$userCSSUri           = $wa->getAsset('style', 'template.user')->getUri();
$fontsCSSUri          = $wa->assetExists('style', 'fontscheme.current') ? $wa->getAsset('style', 'fontscheme.current')->getUri() : '';
$editorCSSUri         = $wa->assetExists('style', 'j-editor-css') ? $wa->getAsset('style', 'j-editor-css')->getUri() : '';
$templateCSS          = ($templateCSSUri !== '') ? '@import url("' . $templateCSSUri . '?' . $mv . '");' : '';
$templateColorsCSS    = ($templateColorsUri !== '') ? '@import url("' . $templateColorsUri . '?' . $mv . '");' : '';
$activeLanguageCSS    = ($activeLanguageCSSUri !== '') ? '@import url("' . $activeLanguageCSSUri . '?' . $mv . '");' : '';
$userCSS              = ($userCSSUri !== '') ? '@import url("' . $userCSSUri . '?' . $mv . '");' : '';
$fontsCSS             = ($fontsCSSUri !== '') ? '@import url("' . $fontsCSSUri . '?' . $mv . '");' : '';
$editorCSS            = ($editorCSSUri !== '') ? '@import url("' . $editorCSSUri . '?' . round(microtime(true) * 1000) . '");' : '';

/* $editorCSS Fallback */
$edCSS = <<<CSS
hr#system-readmore {
  border-bottom: 3px solid #ccc;
  outline: 3px dashed #f00;
  width: 99%;
  margin-left: auto;
  margin-right: auto;
}

span[lang] {
  padding: 2px;
  border: 1px dashed #bbb;
}
span[lang]:after {
  font-size: smaller;
  color: #f00;
  vertical-align: super;
  content: attr(lang);
}

/*
 * For rendering images inserted using the image plugin.
 * Includes image captions using the HTML5 figure element.
 */
figure.image {
  display: inline-block;
  border: 1px solid gray;
  margin: 0 2px 0 1px;
  background: #f5f2f0;
}

figure.align-left {
  float: left;
}

figure.align-right {
  float: right;
}

figure.image img {
  margin: 8px 8px 0 8px;
}

figure.image figcaption {
  margin: 6px 8px 6px 8px;
  text-align: center;
}

/* Basic styles for Table of Contents plugin (toc) */
.mce-toc {
  border: 1px solid gray;
}

.mce-toc h2 {
  margin: 4px;
}

.mce-toc li {
  list-style-type: none;
}
CSS;

$currentEditorCSS = $editorCSS ?: $edCSS;

echo <<<CSS
@charset "UTF-8";
/* Template CSS          */ $templateCSS
/* Template Colours      */ $templateColorsCSS
/* Active Language CSS   */ $activeLanguageCSS
/* User CSS              */ $userCSS
/* Fonts CSS             */ $fontsCSS
/* Styles for the editor */ $currentEditorCSS
/* Inline                */
:root {
  --hue: 214;
  --template-bg-light: #f0f4fb;
  --template-text-dark: #495057;
  --template-text-light: #ffffff;
  --template-link-color: #2a69b8;
  --template-special-color: #001B4C;
  $fontStyles
}
CSS;
