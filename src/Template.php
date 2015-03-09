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
        $templateName = sprintf('theme::%s', $content);
        if ($this->getEngine()->exists($templateName)) {
            $content = $templateName;
        }

        $this->setData([
            'meta' => $this->parseMeta($data),
            'content' => $data->body,
        ]);

        return $this->getEngine()->render($content, $this->getData());
    }
}
