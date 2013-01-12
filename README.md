composer
========
XP Composer integration

To install XP Modules via [Composer](http://getcomposer.org/), you need to add the 
XP Framework downloads and the following scripts to your `composer.json` file.

Example composer.json
---------------------

```json
{
  "repositories": [
    {
      "type"    : "composer",
      "url"     : "http://xp-framework.net/downloads/"
    }
  ],

  "scripts": {
    "post-package-install"   : "\\XP\\Composer::postPackageInstall",
    "post-package-update"    : "\\XP\\Composer::postPackageInstall",
    "post-package-uninstall" : "\\XP\\Composer::postPackageRemove"
  },

  /* Now add your libraries here, as usual. An example using the "gsa-xmlfeed" library */
  "require": {
    "xp-forge/gsa-xmlfeed"  : "1.0.0"
  }
}
```