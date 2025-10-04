<?php

namespace Core\Routing;

use Core\Config\Config;
use Core\Contracts\Http\HttpRequestInterface;
use Core\Contracts\Http\HttpResponseCode;
use Core\Contracts\RouterInterface;
use Core\Contracts\View\MessageType;
use Core\Exceptions\Csrf\CsrfException;
use Core\Http\HttpResponse;
use Core\Security\CsrfTokenManager;
use Core\Tags\MetaTag;
use Core\View\PageRenderer;

class FrontController
{
    private HttpRequestInterface $request;
    private CsrfTokenManager $csrfTokenManager;
    private Config $config;

    /**
     * @param HttpRequestInterface $request
     * @param CsrfTokenManager $csrfTokenManager
     * @param Config $config
     */
    public function __construct(HttpRequestInterface $request, CsrfTokenManager $csrfTokenManager, Config $config)
    {
        $this->request = $request;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function dispatch(): void
    {
        $matched = false;
        /**
         * @var RouterInterface $router
         */
        foreach ($this->getAllRouters() as $router) {
            if ($router->match($this->request->getPath(), $this->request->getMethod())) {
                $matched = true;
                $router->dispatch($this->request);
                break;
            }
        }

        if (!$matched) {
            $this->handleNotFound();
        }
    }

    /**
     * @return void
     */
    private function handleNotFound(): void
    {
        $response = new HttpResponse();
        $response->setCode(HttpResponseCode::NOT_FOUND);

        $content = new PageRenderer("404/index");
        $content->setTitle("Page Not Found");

        $noIndexTag = new MetaTag([
            "name"    => "robots",
            "content" => "noindex, nofollow"
        ]);

        $content->addMetaTag($noIndexTag);
        $response->setContent($content);

        $response->send();
    }

    /**
     * @return array<string>
     */
    private function getAllRouters(): array
    {
        $routers = require CONFIG_PATH . "/generated/routes.php";

        $routerInstances = [];

        foreach ($routers as $router => $paths) {
            $routerInstances[] = new $router($paths);
        }

        return $routerInstances;
    }
}
