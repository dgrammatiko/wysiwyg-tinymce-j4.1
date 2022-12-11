<?php

/**
 * @copyright   (C) 2022 Dimitrios Grammatikogiannis
 * @license     GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\System\TtcTinyMCE\Extension\TtcTinyMCE;

return new class implements ServiceProviderInterface
{
  public function register(Container $container)
  {
    $container->set(
      PluginInterface::class,
      function (Container $container) {
        $dispatcher = $container->get(DispatcherInterface::class);
        $plugin = new TtcTinyMCE(
          $dispatcher,
          (array) PluginHelper::getPlugin('system', 'ttctinymce')
        );
        $plugin->setApplication(Factory::getApplication());

        return $plugin;
      }
    );
  }
};
