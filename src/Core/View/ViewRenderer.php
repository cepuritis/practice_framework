<?php
namespace Core\View;

use Core\Contracts\View\ViewInterface;
use Core\Exception\TemplateNotSetException;
use Core\Helpers\JsonHelper;
use Core\Models\DataObject;
use Core\Tags\LinkTag;
use Core\Tags\MetaTag;
use Core\Tags\ScriptTag;
use RuntimeException;

class ViewRenderer implements ViewInterface
{
    private string $template;
    private ?DataObject $data;

    public function __construct(string $template, ?DataObject $data = null)
    {
        $this->template = $template;
        $this->data = is_null($data) ? new DataObject() : $data;
    }

    public function render(?DataObject $viewData = null): string
    {
        if ($viewData instanceof DataObject) {
            $viewData = $viewData->merge($this->data);
        } else {
            $viewData = $this->data;
        }

        $file = VIEW_PATH . "/{$this->template}.phtml";
        if (!file_exists($file)) {
            throw new RuntimeException("Template not found: {$file}");
        }

        $render = function (string $file, DataObject $data) {
            ob_start();
            include $file;
            return ob_get_clean();
        };

        return $render($file, $viewData);
    }

    public function getTemplateName(): string
    {
        return $this->template;
    }
}