<?php
namespace Controller\Home;

use Core\Attributes\HttpRequest\Route;
use Core\Attributes\HttpRequest\GET;
use Core\HttpRequest;
use Core\HttpResponse;
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
        $meta = new MetaTag(["name"=>"author", "content" => "Aigars Cepuritis"]);
        $script = new ScriptTag("https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js");
        $response->addExternalScript($script);
        $response->addMetaTag($meta);
        $response->setTitle("Home Page");
        $response->render();
    }
}