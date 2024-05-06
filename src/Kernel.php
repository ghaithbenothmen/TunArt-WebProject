<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/packages/*.{yaml,yml}', 'glob');
        $loader->load($confDir.'/packages/nelmio_cors.yaml');

        if (is_file($confDir.'/services.yaml')) {
            $loader->load($confDir.'/services.yaml');
        } elseif (is_file($path = $confDir.'/services.php')) {
            require $path($container->getParameter('kernel.debug'), $this->getEnvironment());
        }
    }
}
