<?php
namespace Mouf\Html\Utils\WebLibraryManager\ComponentInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * This Composer plugin is in charge of the installation of bower files.
 * 
 * @author David Négrier
 */
class BowerInstallerPlugin implements PluginInterface {
	
	public function activate(Composer $composer, IOInterface $io)
	{
		$installer = new BowerInstaller($io, $composer);
		$composer->getInstallationManager()->addInstaller($installer);
	}
}
