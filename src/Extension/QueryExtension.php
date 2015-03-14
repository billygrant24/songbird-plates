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
        $engine->registerFunction('query', [$this, 'getQuery']);
        $engine->registerFunction('pager', [$this, 'getPager']);
    }

    public function getPager($resultSet)
    {
        return new Paginator($resultSet);
    }

    public function getQuery()
    {
        return $this->getContainer()->get('Document.Repository');
    }
}
