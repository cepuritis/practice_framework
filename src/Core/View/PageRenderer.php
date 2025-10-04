<?php

namespace Core\View;

use Core\Contexts\View\PageContext;
use Core\Contracts\View\ViewInterface;
use Core\Models\Data\DataCollection;
use Core\Tags\LinkTag;
use Core\Tags\MetaTag;
use Core\Tags\ScriptTag;
use Core\View\Traits\FlashMessageRenderer;
use Core\View\Traits\UseOldPostData;
use RuntimeException;

class PageRenderer implements ViewInterface
{
    use FlashMessageRenderer;
    use UseOldPostData;

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

    protected array $localScripts = [];


    //TODO Remove when ObjectManager implemented
    public static ?PageRenderer $current = null;

<<<<<<< Updated upstream
=======
    /**
     * @param string $initialTemplate
     * @param string $baseTemplate
     * @param DataCollection|null $data
     */
>>>>>>> Stashed changes
    public function __construct(
        string $initialTemplate,
        string $baseTemplate = self::DEFAULT_BASE_TEMPLATE,
        ?DataCollection $data = null
    ) {
        $this->initialTemplate = $initialTemplate;
        $this->baseTemplate = $baseTemplate;
        $this->data = is_null($data) ? new DataCollection() : $data;
        self::$current = $this;
        $this->addFlashMessagesToData();
    }

    /**
     * @param DataCollection $data
     * @param bool $merge
     * @return void
     */
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
<<<<<<< Updated upstream

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
=======
        $pageContext = new PageContext($this, $this->data);
        $content = (new ViewRenderer($this->initialTemplate, $this->data))->render($viewData, $pageContext);
        return (new ViewRenderer($this->baseTemplate, $viewData))
            ->render($viewData, $pageContext, ['template' => $content]);
>>>>>>> Stashed changes
    }

    /**
     * @param MetaTag $tag
     * @return $this
     */
    public function addMetaTag(MetaTag $tag): self
    {
        $this->metaTags[$tag->getHash()] = $tag;
        return $this;
    }

    /**
     * @param ScriptTag $script
     * @return void
     */
    public function addExternalScript(ScriptTag $script): void
    {
        $this->scriptTags[$script->getHash()] = $script;
    }

    /**
     * @param LinkTag $link
     * @return void
     */
    public function addLinkTag(LinkTag $link): void
    {
        $this->linkTags[$link->getHash()] = $link;
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

    /**
     * @param string $template
     * @param DataCollection|array $data
     * @return string
     */
    public function include(string $template, DataCollection|array $data = []): string
    {
        if (!($data instanceof DataCollection)) {
            $data = new DataCollection($data);
        }
        $view = new ViewRenderer($template, $data);
        $this->views[$view->getTemplateName()] = $view;
        try {
            $content = $view->render($data);
        } catch (\ReflectionException $e) {
        }
        
        return $content ?? "";
    }

    /**
     * @return array
     */
    public function getMetaTags(): array
    {
        return $this->metaTags;
    }

    /**
     * @return array
     */
    public function getScriptTags(): array
    {
        return $this->scriptTags;
    }

    /**
     * @return array
     */
    public function getLinkTags(): array
    {
        return $this->linkTags;
    }

    public function addJs(string $src)
    {
        $script = new ScriptTag($src, true, true);
        $this->scriptTags[md5($src)] = $script;
    }

    public function removeJs(string $src)
    {
        $key = md5($src);
        if (isset($this->scriptTags[$key])) {
            unset($this->scriptTags[$key]);
        }
    }
}
