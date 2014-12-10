<?php
namespace Mouf\Html\Utils\WebLibraryManager\BowerInstaller;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;
use Mouf\MoufManager;
use Mouf\Html\Utils\WebLibraryManager\WebLibraryInstaller;
use Composer\Config;
use Composer\Package\RootPackageInterface;

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
		
		$rootPackage = $this->composer->getPackage();
		
		self::installBowerPackage($target, $this->composer->getConfig(), $moufManager, $rootPackage);
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
    public static function installBowerPackage(PackageInterface $package, Config $config, $moufManager, RootPackageInterface $rootPackage) {
    	if (!$moufManager->has('defaultWebLibraryManager')) {
    		return;
    	}
    	
    	$extra = $package->getExtra();
    	
    	$rootExtra = $rootPackage->getExtra();
    	
		$packageName = explode('/', $package->getName())[1];
    		
    	if (!$moufManager->has("bower.".$packageName)) {

			if (isset($rootExtra['asset-installer-paths']['bower-asset-library'])) {
				$targetDir = rtrim($rootExtra['asset-installer-paths']['bower-asset-library'], '/').'/'.$package->getName().'/';
			} else {
				$targetDir = 'vendor/'.$package->getName().'/';
			}
			
    		$scripts = [];
    		$css = [];
    		if (isset($extra['bower-asset-main'])) {
    			$mainFiles = $extra['bower-asset-main'];
    			if (!is_array($mainFiles)) {
    				$mainFiles = [ $mainFiles ];
    			}
				foreach ($mainFiles as $file) {
					$extension = pathinfo($file,  PATHINFO_EXTENSION);
					if (strtolower($extension) == 'css') {
						$css[] = $targetDir.$file;
					} elseif (strtolower($extension) == 'js') {
						$scripts[] = $targetDir.$file;
					}
				}
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
