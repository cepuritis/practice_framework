<?php
namespace Controller\Home;

use Core\Attributes\HttpRequest\GET;
use Core\Attributes\HttpRequest\Route;
use Core\Http\HttpRequest;
use Core\Http\HttpResponse;
use Core\Tags\LinkTag;
use Core\Tags\MetaTag;
use Core\Tags\ScriptTag;

#[Route('/')]
class Home
{
    #[GET]
    public function getPath(HttpRequest $request)
    {
        $response = new HttpResponse();
        $response->setTemplate("Home/index.phtml");
        $meta = new MetaTag(
            [
                "name" => "author",
                "content" => "Aigars Cepuritis"
            ]
        );
        $script = new ScriptTag("https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js");
        $link = new LinkTag(
            [
                "href" => "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css",
                "rel" => "stylesheet"
            ]
        );
        $response->addExternalScript($script);
        $response->addMetaTag($meta);
        $response->addLinkTag($link);
        $response->setTitle("Home Page");
        $response->render();
    }
}
