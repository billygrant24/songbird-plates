<?php
namespace Songbird\Package\Plates;

use League\Container\ContainerInterface;
use League\Plates\Engine;
use Songbird\PackageProviderAbstract;

class PlatesProvider extends PackageProviderAbstract
{
    protected $provides = [
        'Template'
    ];

    /**
     * @param \League\Container\ContainerInterface $app
     *
     * @return mixed|void
     */
    public function registerPackage(ContainerInterface $app)
    {
        $this->registerEngine($app);

        $template = $app->get('Songbird\Package\Plates\Template');
        $template->setEngine($app->get('Plates.Engine'));

        $app->add('Template', $template);
    }

    /**
     * @param \League\Container\ContainerInterface $app
     */
    protected function registerEngine(ContainerInterface $app)
    {
        $app->add('Plates.Engine', new Engine(null, $app->config('plates.extension')));

        $themeDir = vsprintf('%s/%s', [
            $app->config('plates.templatesDir'),
            $app->config('app.theme')
        ]);

        $app->get('Plates.Engine')->addFolder('theme', $themeDir);
    }
}
