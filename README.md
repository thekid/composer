XP Composer integration
=======================
To install XP Modules via [Composer](http://getcomposer.org/) to use them inside XP Technology, you need to add the XP Framework downloads and the following scripts to your `composer.json` file.

Example composer.json
---------------------
```json
{
  "repositories": [
    { "type" : "composer", "url" : "http://xp-framework.net/downloads/" }
  ],

  "scripts": {
    "post-package-install"   : "\\XP\\Composer::postPackageInstall",
    "post-package-update"    : "\\XP\\Composer::postPackageInstall",
    "post-package-uninstall" : "\\XP\\Composer::postPackageRemove"
  },

  "require": {
    "xp-forge/gsa-xmlfeed"   : "1.0.0"
  }
}
```

When running `composer install` or `composer update`, this will create the
necessary `.pth` files so that the libraries get added to the XP framework's
class path. This will be visible in the output:

```sh
$ composer install
Installing dependencies
  - Installing xp-forge/gsa-xmlfeed (1.0.0)
    Downloading: 100%
    Unpacking archive
    Cleaning up

    XP Class path <vendor/xp-forge/gsa-xmlfeed/src/main/php>
    XP Class path <vendor/xp-forge/gsa-xmlfeed/src/test/php>
Writing lock file
Generating autoload files
```

For all other libraries, the scripts above will generate a `composer.pth` 
file with a single line containing `vendor/autoload.php`. This will be 
required with [pull request 218 merged](https://github.com/xp-framework/xp-framework/pull/218).
This way, their classes can also be used inside XP Technology code 
seamlessly.