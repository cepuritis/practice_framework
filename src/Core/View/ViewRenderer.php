<?php
namespace Core\View;

use Core\Contracts\View\ViewInterface;
use Core\Models\Data\DataCollection;
use Core\View\Traits\FlashMessageRenderer;
use Core\View\Traits\UseOldPostData;
use RuntimeException;

class ViewRenderer implements ViewInterface
{
    use FlashMessageRenderer;
    use UseOldPostData;
    private string $template;
    private DataCollection $data;

    /**
     * @param string $template
     * @param DataCollection|null $data
     */
    public function __construct(string $template, ?DataCollection $data = null)
    {
        $this->template = $template;
        $this->data = is_null($data) ? new DataCollection() : $data;
        $this->addFlashMessagesToData();
    }

    public function render(?DataCollection $viewData = null, object $context = null, array $extraVars = []): string
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

<<<<<<< Updated upstream
        $render = function (string $file, DataCollection $data) {
=======
        $setAdditionalData = fn (&$data) => $this->addAdditionalData($data);
        $render = function (string $file, DataCollection $data) use ($setAdditionalData, $extraVars) {
            extract($extraVars);
            $setAdditionalData($data);
>>>>>>> Stashed changes
            ob_start();
            include $file;
            return ob_get_clean();
        };

        if ($context) {
            $render = $render->bindTo($context, get_class($context));
        }

        return $render($file, $viewData);
    }

    /**
     * @param DataCollection $data
     * @return void
     */
    private function addAdditionalData(DataCollection &$data): void
    {
        //We could use Data Enhancer property and addDataEnhancer function to add callbacks for decoupling
        $data['csrf'] = app()->make(CsrfTokenManager::class)->input();
        $this->setOldPostData($data);
    }

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        return $this->template;
    }
}
