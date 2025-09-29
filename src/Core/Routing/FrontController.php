<?php

namespace Core\Routing;

use Core\Contracts\Http\HttpRequestInterface;
use Core\Contracts\Http\HttpResponseCode;
use Core\Contracts\RouterInterface;
use Core\Http\HttpResponse;
use Core\Tags\MetaTag;
use Core\View\PageRenderer;

class FrontController
{
    private HttpRequestInterface $request;
    public function __construct(HttpRequestInterface $request)
    {
        $this->request = $request;
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
