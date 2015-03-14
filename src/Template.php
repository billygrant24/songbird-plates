<?php
namespace Songbird\Package\Plates;

use org\bovigo\vfs\vfsStream;
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

        $this->setData(['meta' => $this->parseMeta($data)]);
        $this->setData(['content' => $this->replacePlaceholders($data['body'])]);

        return $this->getEngine()->render($content, $this->getData());
    }

    /**
     * Render a template from a string.
     *
     * @param string $content
     *
     * @return mixed
     */
    public function renderString($content)
    {
        vfsStream::create(['template.php' => $this->replacePlaceholders($content)]);

        return $this->getEngine()->render('virtual::template', $this->getData());
    }
}
