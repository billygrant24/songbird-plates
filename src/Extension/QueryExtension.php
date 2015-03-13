<?php
namespace Songbird\Package\Plates\Extension;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Songbird\Pagination\Paginator;

class QueryExtension implements ExtensionInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function register(Engine $engine)
    {
        $engine->registerFunction('paginator', [$this, 'paginator']);
    }

    public function paginator($resultSet)
    {
        return new Paginator($resultSet);
    }
}
