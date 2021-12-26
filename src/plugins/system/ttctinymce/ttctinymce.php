<?php
/**
 * @copyright   (C) 2021 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Plugin\System\Ttctinymce\PluginTraits\Main;

/**
 * TinyMCE true WYSIWYG Editor Plugin
 */
class PlgSystemTtctinymce extends CMSPlugin
{
  use Main;
  /**
   * Application object.
   *
   * @var  \Joomla\CMS\Application\CMSApplication
   */
  protected $app;

  /**
   * @return  void
   */
  public function onBeforeCompileHead(): void
  {
    $doc = $this->app->getDocument();

    if ($doc->getType() !== 'html') {
      return;
    }

    $data = $doc->getHeadData();
    if (!isset($data['scriptOptions']['plg_editor_tinymce']) || !isset($data['scriptOptions']['plg_editor_tinymce']['tinyMCE'])) {
      return;
    }

    $this->doTheMagic($doc, $data);
  }
}
