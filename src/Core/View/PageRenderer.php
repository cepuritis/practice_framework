<?php

namespace Core\View;

use Core\Contracts\View\ViewInterface;
use Core\Models\Data\DataCollection;
use Core\Tags\LinkTag;
use Core\Tags\MetaTag;
use Core\Tags\ScriptTag;
use Core\View\Traits\FlashMessageRenderer;
use RuntimeException;

class PageRenderer implements ViewInterface
{
    use FlashMessageRenderer;

    protected ?DataCollection $data;

    /**
     * @var array<ViewInterface> $views
     */
    protected array $views = [];
    const DEFAULT_BASE_TEMPLATE = "Base/index";
    protected string $baseTemplate;
    protected string $initialTemplate;
    protected array $metaTags = [];
    protected array $linkTags = [];
    protected array $scriptTags = [];


    //TODO Remove when ObjectManager implemented
    public static ?PageRenderer $current = null;

    public function __construct(
        string $initialTemplate,
        string $baseTemplate = self::DEFAULT_BASE_TEMPLATE,
        ?DataCollection $data = null
    ) {
        $this->initialTemplate = $initialTemplate;
        $this->baseTemplate = VIEW_PATH . "/{$baseTemplate}.phtml";
        $this->data = is_null($data) ? new DataCollection() : $data;
        self::$current = $this;
        $this->addFlashMessagesToData();
    }

    public function setData(DataCollection $data, bool $merge = true)
    {
        if ($merge) {
            $data = $data->merge($this->data);
        }

        $this->data = $data;
    }

    /**
     * @param DataCollection $viewData
     * @return string
     */
    public function render(?DataCollection $viewData = null): string
    {
        if ($viewData instanceof DataCollection) {
            $viewData = $viewData->merge($this->data);
        } else {
            $viewData = $this->data;
        }

        $templatePath =  VIEW_PATH . "/{$this->initialTemplate}.phtml";

        $render = function (DataCollection $data) use ($templatePath) {
            if (!file_exists($templatePath) || $templatePath === $this->baseTemplate) {
                throw new RuntimeException("Invalid template file specified " . $templatePath);
            }
            $template = null;
            ob_start();
            include $templatePath;
            $template = ob_get_clean();
            ob_start();
            include $this->baseTemplate;
            return ob_get_clean();
        };


        return $render($viewData);
    }

    /**
     * @param MetaTag $tag
     * @return $this
     */
    public function addMetaTag(MetaTag $tag): self
    {
        $this->metaTags[] = $tag;
        return $this;
    }

    /**
     * @param ScriptTag $script
     * @return void
     */
    public function addExternalScript(ScriptTag $script)
    {
        $this->scriptTags[] = $script;
    }

    /**
     * @param LinkTag $link
     * @return void
     */
    public function addLinkTag(LinkTag $link)
    {
        $this->linkTags[] = $link;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->data->setTitle($title);
        return $this;
    }

    /**
     * @param ViewInterface $view
     * @return void
     */
    public function addView(ViewInterface $view): void
    {
        $this->views[$view->getTemplateName()] = $view;
    }

    /**
     * @return ViewInterface[]
     */
    public function getViews(): array
    {
        return $this->views;
    }

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        return $this->initialTemplate;
    }
}
