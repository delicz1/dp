<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 *
 * @author necas
 */
class AppKernel extends Kernel {

    /**
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct($environment, $debug) {
        date_default_timezone_set('Europe/Prague');
        parent::__construct($environment, $debug);
    }

    /**
     * @return array|\Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    public function registerBundles() {
        \Proj\Base\Object\Application::_init($this->getRootDir());

        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),                      // WkHtml2Pdf
            new JMose\CommandSchedulerBundle\JMoseCommandSchedulerBundle(),     // Cron planovac
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),   // Doctrine extensions
            // Proj
            new Nil\NilBundle(),
            new Proj\Base\ProjBaseBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
//            $bundles[] = new Proj\Test\ProjTestBundle();
            $bundles[] = new Proj\BussinesTrip\ProjBussinesTripBundle();
        }

        return $bundles;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader) {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
