<?php
namespace Controller\Home;

use Core\Attributes\HttpRequest\GET;
use Core\Attributes\HttpRequest\Route;
use Core\Http\HttpRequest;
use Core\Http\HttpResponse;
use Core\Models\DataObject;
use Core\Tags\LinkTag;
use Core\Tags\MetaTag;
use Core\Tags\ScriptTag;
use Core\View\PageRenderer;

#[Route('/')]
class Home
{
    #[GET]
    public function getPath(HttpRequest $request)
    {
        $view = new PageRenderer("Home/index");
        $view->setData(new DataObject(['firstName' => 'Aigars', 'lastName' => 'Cepuritis']));
        $view->setTitle("Home Page");
        $meta = new MetaTag(
            [
                "name" => "author",
                "content" => "Aigars Cepuritis"
            ]
        );
        $view->addMetaTag($meta);

//        $script = new ScriptTag("https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js");
//        $link = new LinkTag(
//            [
//                "href" => "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css",
//                "rel" => "stylesheet"
//            ]
//        );
//        $content->addExternalScript($script);
//        $content->addLinkTag($link);

        $response = new HttpResponse($view);
        $response->send();
    }
}
