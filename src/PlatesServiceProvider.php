<?php
namespace Songbird\Package\Plates;

use League\Container\ContainerInterface;
use League\Plates\Engine;
use Songbird\PackageProviderAbstract;

class PlatesServiceProvider extends PackageProviderAbstract
{
    protected $provides = [
        'Template'
    ];

    /**
     * @param \League\Container\ContainerInterface $app
     */
    public function registerPackage(ContainerInterface $app)
    {
        $config = $app->get('Config');

        $this->registerEngine($app);
        $this->registerExtensions($app);

        $template = $app->resolve('Songbird\Package\Plates\Template');

        $template->setEngine($app->get('Plates.Engine'));

        $template->getEngine()->addData([
            'siteTitle' => $config->get('vars.siteTitle'),
            'baseUrl' => $config->get('vars.baseUrl'),
            'themeDir' => $config->get('vars.baseUrl') . '/themes/' . $config->get('app.theme'),
            'dateFormat' => $config->get('vars.dateFormat'),
            'excerptLength' => $config->get('vars.excerptLength'),
        ]);

        $app->add('Template', $template);
    }

    /**
     * Register all Plates extensions required by Songbird.
     *
     * @param \League\Container\ContainerInterface $app
     */
    protected function registerExtensions(ContainerInterface $app)
    {
        $app->get('Plates.Engine')->loadExtensions([
            $app->resolve('Songbird\Package\Plates\Extension\QueryExtension'),
            $app->resolve('Songbird\Package\Plates\Extension\FragmentExtension'),
        ]);
    }

    /**
     * @param \League\Container\ContainerInterface $app
     */
    protected function registerEngine(ContainerInterface $app)
    {
        $config = $this->getContainer()->get('Config');

        $app->add('Plates.Engine', new Engine(null, $config['plates.extension']));

        $themeDir = vsprintf('%s/%s', [$config['plates.templatesDir'], $config['app.theme']]);
        $app->get('Plates.Engine')->addFolder('theme', $themeDir);
    }
}
