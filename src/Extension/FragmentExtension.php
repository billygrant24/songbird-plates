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
     * @return \JamesMoss\Flywheel\Result
     */
    public function fragment($id, array $params = [])
    {
        $fragment = $this->getContainer()->get('Fragment.Repository')->find($id);
        $fragment = $this->getContainer()->get('Document.Transformer')->apply($fragment);

        return $this->getContainer()->get('Template')->renderString($fragment['body']);
    }
}