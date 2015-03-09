<?php
namespace Songbird\Package\Plates;

use JamesMoss\Flywheel\DocumentInterface;
use League\Plates\Engine;
use Songbird\Template\TemplateInterface;

class Template implements TemplateInterface
{
    /**
     * @var object
     */
    protected $engine;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Render a template.
     *
     * @param string $content
     * @param array  $data
     *
     * @return mixed
     */
    public function render($content, $data = null)
    {
        if ($this->getEngine()->exists(sprintf('theme::%s', $content))) {
            $content = sprintf('theme::%s', $content);
        }

        $this->setData([
            'meta' => $this->parseMeta($data),
            'content' => $data->body,
        ]);

        return $this->getEngine()->render($content, $this->getData());
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = array_merge($this->getData(), $data);
    }

    /**
     * @return \Twig_Environment $twig
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param \League\Plates\Engine|\Twig_Environment $plates
     */
    public function setEngine(Engine $plates)
    {
        $this->engine = $plates;
    }

    /**
     * @param \JamesMoss\Flywheel\DocumentInterface $document
     *
     * @return array
     */
    protected function parseMeta(DocumentInterface $document)
    {
        $meta = [];
        foreach ($document as $key => $value) {
            if (strpos($key, '_', 0) || $key === 'settings') {
                continue;
            }

            $meta[$key] = $value;
        }

        return $meta;
    }
}
