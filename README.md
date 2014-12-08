WebLibraryManager: Installer for Bower packages
===============================================

Thanks to François Pluchino, it is now possible to declare Bower dependencies
right [into your composer.json files](https://github.com/francoispluchino/composer-asset-plugin/).

The **WebLibraryManager** has a built in support for Bower packages. If you import one of those packages
in your project, the **WebLibraryManager** will detect these packages and will automatically create the **WebLibrary** instances
matching those packages.

For instance:

**composer.json**
```
{
	"require": {
		"bower-asset/bootstrap": "dev-master"
	}
}
```

<a href="http://mouf-php.com/packages/mouf/html.utils.weblibrarymanager" class="btn btn-primary">Check the <strong>WebLibraryManager</strong> documentation to learn more about it</a>

