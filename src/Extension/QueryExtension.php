<?php
namespace Songbird\Package\Plates\Extension;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Songbird\Document\Pagination\Paginator;

class QueryExtension implements ExtensionInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function register(Engine $engine)
    {
        $engine->registerFunction('query', [$this, 'query']);
        $engine->registerFunction('paginator', [$this, 'paginator']);
    }

    /**
     * Provides an interface to query content from template files.
     *
     * @param array $params
     *
     * @return \JamesMoss\Flywheel\Result
     */
    public function query(array $params = [])
    {
        $config = $this->getContainer()->get('Config');
        $repo = $this->getContainer()->get('Repo.Documents');

        $collection = $repo->query();

        if (isset($params['type'])) {
            $collection->where('_type', '==', $params['type']);
            unset($params['_type']);
        }

        $queries = isset($params['query']) ? $params['query'] : [];
        foreach ($queries as $key => $value) {
            $q = $this->detectOps($key);
            $collection->where($q['field'], $q['operator'], $value);
        }

        $orderBy = isset($params['orderBy']) ? $params['orderBy'] : $config['vars.sorting'];
        $collection->orderBy($orderBy);

        $limit = isset($params['limit']) ? $params['limit'] : $config['vars.perPage'];
        $collection->paginate($limit);

        return $collection->execute();
    }

    protected function detectOps($key)
    {
        $result['field'] = $key;
        $result['operator'] = '==';

        if (strpos($key, ' ')) {
            $parts = explode(' ', $key);
            $result['field'] = trim($parts[0]);
            $result['operator'] = trim($parts[1]);
        }

        return $result;
    }

    public function paginator($resultSet)
    {
        return new Paginator($resultSet);
    }
}
