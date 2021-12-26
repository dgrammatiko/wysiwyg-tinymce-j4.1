
<?php
defined('_JEXEC') || die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Adapter\PluginAdapter;
use Joomla\CMS\Installer\InstallerScript;

class plgSystemTtctinymceInstallerScript extends InstallerScript
{
  public function install(PluginAdapter $parent)
  {
    $parentInstance = $parent->getParent()->getInstance();
    $paths = $parentInstance->get('paths');
    if (is_file($paths['source'] . '/extras/jeditor.php')) {
      if (is_dir(JPATH_ROOT . '/templates/cassiopeia')) {
        copy(
          $paths['source'] . '/extras/jeditor.php',
          JPATH_ROOT . '/templates/cassiopeia/jeditor.php'
        );
      }
      if (!is_dir(JPATH_ROOT . '/media/templates/site/cassiopeia')) {
        mkdir(JPATH_ROOT . '/media/templates/site/cassiopeia/js', 0755, true);
      }
      if (is_dir(JPATH_ROOT . '/media/templates/site/cassiopeia')) {
        copy(
          $paths['source'] . '/extras/formats.json',
          JPATH_ROOT . '/media/templates/site/cassiopeia/js/formats.json'
        );
        copy(
          $paths['source'] . '/extras/style_formats.json',
          JPATH_ROOT . '/media/templates/site/cassiopeia/js/style_formats.json'
        );
      }
    }
  }

  public function postflight($type, PluginAdapter $parent)
  {
    // Enable the plugin
    if ($type === 'install' || $type === 'discover_install') {
      $db = Factory::getDbo();
      $query = $db->getQuery(true)
        ->update('#__extensions')
        ->set($db->qn('enabled') . ' = 1')
        ->where($db->qn('type') . ' = ' . $db->q('plugin'))
        ->where($db->qn('element') . ' = ' . $db->q('ttctinymce'))
        ->where($db->qn('folder') . ' = ' . $db->q('system'));
      $db->setQuery($query);
      try {
        $db->execute();
      } catch (\Exception $e) {
        // var_dump($e);
      }
    }
  }
}
