<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \App\Bundle\AppBundle()
        );

        if ('dev' === $this->getEnvironment()) {
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');

        $isDevEnvironment = 'dev' === $this->getEnvironment();
        $isTestEnvironment = 'test' === $this->getEnvironment();

        $loader->load(function (ContainerBuilder $container) use ($isDevEnvironment, $isTestEnvironment) {
            if ($isDevEnvironment) {
                $container->loadFromExtension('web_profiler', array(
                    'toolbar' => true,
                ));
            }

            $routingFilename = $isDevEnvironment ? 'routing_dev.yml' : 'routing.yml';
            $container->loadFromExtension('framework', array(
                'test'   => $isTestEnvironment,
                'router' => array(
                    'resource' => '%kernel.root_dir%/config/'.$routingFilename,
                )
            ));

            $container->setParameter(
                'monolog_action_level',
                $isDevEnvironment ? 'debug' : 'error'
            );
        });
    }
}
