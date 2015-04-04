<?php
namespace Songbird\Package\Plates;

use Songbird\Template\TemplateAbstract;

class Template extends TemplateAbstract
{
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
        $this->setData([
            'content' => $data['body'],
            'meta' => array_except($data, 'body'),
        ]);

        return $this->getEngine()->render(sprintf('theme::%s', $content), $this->getData());
    }
}
