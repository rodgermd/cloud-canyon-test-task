<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 15:18
 */

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AppExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $dir         = __DIR__ . '/../Resources/config';
        $fileLocator = new FileLocator($dir);
        $finder      = new Finder();
        $finder->files()->name('*.yml')->notName('routing.yml')->in($dir);

        $ymlLoader = new Loader\YamlFileLoader($container, $fileLocator);
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $ymlLoader->load($file->getRelativePathname());
        }
    }
}