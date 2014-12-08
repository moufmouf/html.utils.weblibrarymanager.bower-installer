<?php
namespace Mouf\Html\Utils\WebLibraryManager\ComponentInstaller;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;
use Mouf\MoufManager;
use Mouf\Html\Utils\WebLibraryManager\WebLibraryInstaller;
use Composer\Config;

/**
 * This Composer installer is in charge of the installation of bower files in the Mouf framework.
 * 
 * @author David Négrier
 */
class BowerInstaller extends LibraryInstaller
{
    /**
	 * {@inheritDoc}
	 */
	public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
	{
		parent::update($repo, $initial, $target);
		
		if (!file_exists(__DIR__.'/../../../../mouf/Mouf.php') || !file_exists(__DIR__.'/../../../../config.php')) {
			continue;
		}
		require_once(__DIR__.'/../../../../mouf/Mouf.php');
		
		$moufManager = MoufManager::getMoufManager();
		self::installBowerPackage($target, $this->composer->getConfig(), $moufManager);
	}
		
	/**
	 * {@inheritDoc}
	 */
	public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
	{
		parent::uninstall($repo, $package);
		
		if (!file_exists(__DIR__.'/../../../../mouf/Mouf.php') || !file_exists(__DIR__.'/../../../../config.php')) {
			continue;
		}
		require_once(__DIR__.'/../../../../mouf/Mouf.php');
		
		$moufManager = MoufManager::getMoufManager();
		
		if ($moufManager->has("bower.".$package->getName())) {
			$moufManager->removeComponent("bower.".$package->getName());
		}
		$moufManager->rewriteMouf();
	}
	
    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'bower-asset' === $packageType;
    }
    
    /**
     * 
     * @param PackageInterface $package
     * @param Config $config
     * @param MoufManager $moufManager
     */
    public static function installBowerPackage(PackageInterface $package, Config $config, $moufManager) {
    	if (!$moufManager->has('defaultWebLibraryManager')) {
    		return;
    	}
    	
    	$extra = $package->getExtra();
    	
	$packageName = explode('/', $package->getName())[1];
    		
    	if (!$moufManager->has("bower.".$packageName)) {

		$targetDir = 'vendor/'.$package->getName().'/';
    	
    		$scripts = [];
    		$css = [];
    		if (isset($extra['bower-asset-main'])) {
			foreach ($extra['bower-asset-main'] as $file) {
				$extension = pathinfo($file,  PATHINFO_EXTENSION);
				if (strtolower($extension) == 'css') {
					$css[] = 
				}
			}
    			$scripts = array_map(function($script) use ($targetDir) {
    				return $targetDir.'/'.$script;
    			}, $extra['bower-asset-main']);
    		}
    	
    		$css = [];
    		if (isset($extra['component']['styles'])) {
    			$css = array_map(function($script) use ($packageName, $baseUrl) {
    				return $targetDir.'/'.$script;
    			}, $extra['component']['styles']);
    		}
    	
    		$deps = [];
    		/*if (isset($extra['component']['deps'])) {
    			$deps = array_map(function($script) {
    				return "bower.".$script;
    			}, $extra['component']['css']);
    		}*/
    	
    		WebLibraryInstaller::installLibrary("bower.".$packageName, $scripts, $css, $deps, true, $moufManager);
    	}
    	$moufManager->rewriteMouf();	 
    }
}
