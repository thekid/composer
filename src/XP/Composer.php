<?php
  namespace XP;

  use Composer\Script\Event;
  use Composer\Package\PackageInterface;

  /**
   * XP Composer integration
   *
   * Creates .pth files post-install
   */
  class Composer {

    /**
     * Find shortest path notation from a given path relative to a given
     * container. Returns a relative path if path is inside container,
     * e.g. path = /usr/local/bin, container= /usr/local, return= bin.
     * 
     * @param  string $path
     * @param  string $container
     * @return string
     */
    protected static function findShortestPath($path, $container) {
      $l= strlen($container);
      return 0 === strncmp($path, $container, $l) ? substr($path, $l + 1) : $path;
    }

    /**
     * Composes a path from a given argument list
     *
     * @param  string...
     * @return string
     */
    protected static function path() {
      return implode(DIRECTORY_SEPARATOR, func_get_args());
    }

    /**
     * Calculates .pth file for a given package
     * 
     * @param  PackageInterface $package
     * @return string
     */
    protected static function pthFileFor(PackageInterface $package) {
      return strtr($package->getUniqueName(), '/\\?*:;<>|"\'', '____________').'.pth';
    }

    /**
     * Post-package install hook
     * 
     * @param  Event $event
     */
    public static function postPackageInstall(Event $event) {
      $package= $event->getOperation()->getPackage();
      $out= $event->getIO();

      if ('xp-module' === $package->getType()) {
        $out->write('[XP] Handling '.$event->getName().' '.$package->getUniqueName());

        // Calculate paths
        $target= realpath($event->getComposer()->getInstallationManager()->getInstallPath($package));
        $cwd= realpath(getcwd());
        $rel= strtr(self::findShortestPath($target, $cwd), DIRECTORY_SEPARATOR, '/').'/';

        // Create .pth file from all .pth files inside package
        $pth= fopen(self::path($cwd, self::pthFileFor($package)), 'wb');
        foreach (glob(self::path($target, '*.pth')) as $pthfile) {
          $out->write('[XP] '.$pthfile); 
          foreach (file($pthfile) as $line) {
            if ('' === $line || '#' === $line{0}) continue;
            if ('!' === $line{0}) {
              fwrite($pth, '!'.$rel.$line);
            } else if ('~' === $line{0}) {
              fwrite($pth, $line);
            } else {
              fwrite($pth, $rel.$line);
            }
          }
        }
        fclose($pth);
      } else {
        // DEBUG $out->write('[XP] Not handling '.$package->getType().' ');
      }
    }

    /**
     * Post-package remove hook
     * 
     * @param  Event $event
     */
    public static function postPackageRemove(Event $event) {
      $package= $event->getOperation()->getPackage();
      $out= $event->getIO();

      if ('xp-module' === $package->getType()) {
        $out->write('[XP] Handling '.$event->getName().' '.$package->getUniqueName());
        unlink(self::path(realpath(getcwd()), self::pthFileFor($package)));
      } else {
        // DEBUG $out->write('[XP] Not handling '.$package->getType().' ');
      }
    }
  }
?>