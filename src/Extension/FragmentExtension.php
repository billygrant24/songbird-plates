<?php
namespace Songbird\Package\Plates\Extension;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class FragmentExtension implements ExtensionInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function register(Engine $engine)
    {
        $engine->registerFunction('fragment', [$this, 'fragment']);
    }

    /**
     * Renders a partial document.
     *
     * @param string $id
     * @param array  $params
     *
     * @return mixed
     */
    public function fragment($id, array $params = [])
    {
        $app = $this->getContainer();

        $fragment = $app->get('Fragment.Repository')->find($id);
        $fragment['body'] = $app->get('CommonMark')->convertToHtml($fragment['body']);

        return $app->get('Template')->renderString($fragment['body']);
    }
}