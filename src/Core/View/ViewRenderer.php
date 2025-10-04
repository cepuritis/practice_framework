<?php
namespace Core\View;

use Core\Contracts\View\ViewInterface;
use Core\Models\Data\DataCollection;
use Core\View\Traits\FlashMessageRenderer;
use RuntimeException;

class ViewRenderer implements ViewInterface
{
    use FlashMessageRenderer;
    private string $template;
    private ?DataCollection $data;

    public function __construct(string $template, ?DataCollection $data = null)
    {
        $this->template = $template;
        $this->data = is_null($data) ? new DataCollection() : $data;
        $this->addFlashMessagesToData();
    }

    /**
     * @throws \ReflectionException
     */
    public function render(?DataCollection $viewData = null): string
    {
        if ($viewData instanceof DataCollection) {
            $viewData = $viewData->merge($this->data);
        } else {
            $viewData = $this->data;
        }

        $file = VIEW_PATH . "/{$this->template}.phtml";
        if (!file_exists($file)) {
            throw new RuntimeException("Template not found: {$file}");
        }

        $render = function (string $file, DataCollection $data) {
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