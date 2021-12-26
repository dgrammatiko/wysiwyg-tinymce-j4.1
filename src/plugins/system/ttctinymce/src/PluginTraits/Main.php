<?php
/**
 * @copyright   (C) 2021 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later;
 */
namespace Joomla\Plugin\System\Ttctinymce\PluginTraits;

\defined('_JEXEC') || die();

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use RuntimeException;
use stdClass;

trait Main
{
  /**
   * Do the magic.
   *
   * @return  void
   */
  public function doTheMagic($doc, $data): void
  {
    $template = $this->getActiveSiteTemplate();
    // Don't break...
    if (!isset($template->template)) return;

    $options = $data['scriptOptions']['plg_editor_tinymce']['tinyMCE'];
    if (!is_array($options) || count($options) === 0 || !isset($options['default'])) {
      return;
    }

    $tinyMCE = new stdClass;
    $tinyMCE->tinyMCE = [];

    // WYSIWYG CSS
    if (
      is_file(JPATH_ROOT . '/templates/' . $template->template . '/jeditor.php')
      || ($template->parent !== '' && is_file(JPATH_ROOT . '/templates/' . $template->parent . '/jeditor.php'))
    ) {
      // The plugin importcss needs to be removed if loaded.
      $options['default']['plugins'] = str_replace('importcss,', '', $options['default']['plugins']);
      $options['default']['content_css'] = Uri::root() . 'index.php?tmpl=jeditor&v=' . $doc->getMediaVersion();
      $options['default']['importcss_append'] = false;
      $options['default']['importcss_merge_classes'] = false;
    }

    // WYSIWYG Custom Styles
    if ((isset($template->inheritable) && $template->inheritable) && is_file(JPATH_ROOT . '/media/templates/site/' . $template->template . '/js/style_formats.json')) {
      try {
        $tmpStylesFormat = json_decode(file_get_contents(JPATH_ROOT . '/media/templates/site/' . $template->template . '/js/style_formats.json'), true);
      } catch (Exception $e) {
      }
    } elseif ((isset($template->parent) && $template->parent !== '') && is_file(JPATH_ROOT . '/media/templates/site/' . $template->parent . '/js/style_formats.json')) {
      try {
        $tmpStylesFormat = json_decode(file_get_contents(JPATH_ROOT . '/media/templates/site/' . $template->parent . '/js/style_formats.json'), true);
      } catch (Exception $e) {
      }
    }
    if (isset($tmpStylesFormat) && is_array($tmpStylesFormat)) {
      $options['default']['style_formats'] = array_merge($options['default']['style_formats'], (array) $tmpStylesFormat);
      $options['default']['style_formats_merge'] = false;
    }

    // WYSIWYG Custom Formats
    if ((isset($template->inheritable) && $template->inheritable) && is_file(JPATH_ROOT . '/media/templates/site/' . $template->template . '/js/formats.json')) {
      try {
        $tmpFormats = (array) json_decode(file_get_contents(JPATH_ROOT . '/media/templates/site/' . $template->template . '/js/formats.json'));
      } catch (Exception $e) {
      }
    } elseif ((isset($template->parent) && $template->parent !== '') && is_file(JPATH_ROOT . '/media/templates/site/' . $template->parent . '/js/formats.json')) {
      try {
        $tmpFormats = (array) json_decode(file_get_contents(JPATH_ROOT . '/media/templates/site/' . $template->parent . '/js/formats.json'));
      } catch (Exception $e) {
      }
    }
    if (isset($tmpFormats) && $tmpFormats !== false) {
      $options['default']['formats'] = $tmpFormats;
      // $options['default']['fontsize_formats'] = '.75em .8em .9em 1em 1.1em 1.2em'; // 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt';
      // $options['default']['font_formats'] = 'Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats';
    }

    $tinyMCE->tinyMCE['default'] = $options['default'];

    // Per textfield options
    foreach ($options as $key => $value) {
      $tinyMCE->tinyMCE[$key] = $value;
    }

    $doc->addScriptOptions('plg_editor_tinymce', $tinyMCE, true);
  }

  /**
   * Get the active Site template
   *
   * @return  stdClass
   */
  protected function getActiveSiteTemplate()
  {
    $db = Factory::getContainer()->get('db');
    $query = $db->getQuery(true)
      ->select('*')
      ->from($db->quoteName('#__template_styles'))
      ->where([$db->quoteName('client_id') . ' = 0', $db->quoteName('home') . ' = ' . $db->quote('1')]);
    $db->setQuery($query);

    try {
      return $db->loadObject();
    } catch (RuntimeException $e) {
      $this->app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
      return new stdClass;
    }
  }
}
