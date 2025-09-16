
# Practice Framework

I wrote this small php Framework for practice purposes and to review some of forgotten php functionality that's why 'spl_autoload_register' is used here instead of composer psr4 autoloader.

# Usage
## Controller
The central part of creating a new page, routes and methods are matched using php Attribute syntax. Function name does not matter here the only thing that matters is the specified attribute.

```php
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
        $response = new HttpResponse($view);
        $response->send();
    }
}

```

!!!! After adding a new Method or route you need to regenerate routes by cli command bin/console generate:routes  . This will create routes.php file in config/generated folder.


The route is matched by frontController which also pass in HttpRequest object into the method call. All the routes in Controller directory are managed by defaultRouter, if needed new Routers Can be created, but this functionality was mainly added for additional training complexity.

## Response
To render a page an object extending HttpAbstractResponse or implementing HttpResponseInterface must be used, Depending on the response type ```setContent``` function must be called before calling ```$response->send()```.

For regular page rendering ```PageRenderer``` object must be passed as content (ViewInterface is expected). The constructor for this object takes the location of the phtml template relative to ```src/View``` directory without '.phml' this template is gonna be inserted inside of Base scaffolding template which is located in ```src/View/Base/index.phtml```. Option second parameter can be passed if different Base template is preferred.

For JSON response use JsonRenderer and HttpJsonResponse instances respectively. Example can be found ir ```src/Controller/Json/JsonTestData.php```


## ViewInterface
To include a one template in another ViewHelper::include() method can be used specifying the template file and if necessary data that can be used in the included template, data can be either DataObject or an array , but it will be converted to DataObject regardless. It can be accesses through ```$data``` variable from the included template.
DataObject uses magic __call method to store and get keys for example if you pass an array ['firsName' => 'john', 'lastName' => 'doe'] in the template file this can be used as $data->getFirstName();, $data->getLastName(); The scope is isolated by using Closures so if you do not explicitly send data from
parent template to the child template, it won't be available.

```php
<?php /** @var \Core\Models\DataObject $data */ ?>
<header class="w-full bg-blue-600 text-white py-16 px-4 md:px-8 text-center">
    <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to the Home Page</h1>
    <p class="text-lg md:text-xl max-w-2xl mx-auto">
        This is a simple, clean layout using Tailwind CSS for a modern homepage look.
    </p>
</header>
<?= \Core\Helpers\ViewHelper::include('Navigation/nav', $data)?>
<main class="py-12 px-4 md:px-8 max-w-5xl mx-auto">
    <div class="bg-white shadow-md rounded-lg p-6 md:p-10 text-gray-800">
        <h2 class="text-2xl font-semibold mb-4">Main Content</h2>
        <p class="text-base md:text-lg leading-relaxed">
            This is the main content of the home page. You can add cards, sections, or any components here.
            Tailwind makes it easy to adjust spacing, typography, and colors responsively.
        </p>
    </div>
</main>

```

## Styling
The framework uses vite and tailwind, if you add any new tailwind classes that havent been used yet in the project you have to run ```npm run build:css``` this will generate a css file in config/generated/assets. The generated css name contains hash which is diffeent each time and is inserted into the app using ```ViewHelper::getMainStylesheet()``` static method which gets the fileName from the vite manifest.json file, so need to be careful with caching.

## CLI tool

You can add your own commands by specifying the command and class that processes it inside of ``` Cli/Commands.php ``` the class must implement CommandInterface