<?php
namespace Songbird\Package\Plates;

use League\Container\ContainerInterface;
use League\Plates\Engine;
use org\bovigo\vfs\vfsStream;
use Songbird\PackageProviderAbstract;

class PlatesProvider extends PackageProviderAbstract
{
    protected $provides = [
        'Template'
    ];

    /**
     * @param \League\Container\ContainerInterface $app
     */
    public function registerPackage(ContainerInterface $app)
    {
        $this->registerEngine($app);
        $this->registerExtensions($app);

        $template = $app->get('Songbird\Package\Plates\Template');
        $template->setEngine($app->get('Plates.Engine'));

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
            $app->get('Songbird\Package\Plates\Extension\QueryExtension'),
            $app->get('Songbird\Package\Plates\Extension\FragmentExtension'),
        ]);
    }

    /**
     * @param \League\Container\ContainerInterface $app
     */
    protected function registerEngine(ContainerInterface $app)
    {
        $app->add('Plates.Engine', new Engine(null, $app->config('plates.extension')));

        $themeDir = vsprintf('%s/%s', [$app->config('plates.templatesDir'), $app->config('app.theme')]);
        $app->get('Plates.Engine')->addFolder('theme', $themeDir);

        // We're registering a vfs to allow us to render strings. Useful for fragments.
        vfsStream::setup('virtual');
        $app->get('Plates.Engine')->addFolder('virtual', vfsStream::url('virtual'), 'theme');
    }
}
