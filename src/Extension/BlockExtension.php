<?php
namespace Songbird\Package\Plates\Extension;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class BlockExtension implements ExtensionInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function register(Engine $engine)
    {
        $engine->registerFunction('block', [$this, 'getBlock']);
    }

    /**
     * Renders a partial document.
     *
     * @param string $id
     * @param array  $params
     *
     * @return mixed
     */
    public function getBlock($id, array $params = [])
    {
        $app = $this->getContainer();

        $block = $app->get('Repository.Block')->find($id);
        $block['body'] = $app->get('CommonMark')->convertToHtml($block['body']);

        return $app->get('Template')->renderString($block['body']);
    }
}