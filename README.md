Some parts might not be up to date !

# Practice Framework

I wrote this small php Framework for practice purposes and to review some of forgotten php functionality that's why 'spl_autoload_register' is used here instead of composer psr4 autoloader.

# Usage
To start a ready to use project docker configuration is providedm, just run ```docker compose up```
Nginx container has port 8080 published so the app can be accessed from ```http://localhost:8080```
## App Configuration
Configuration is done in config/env.php file you can refer to env.example.php file. All possible configuration schema is declared in env.schema.php as well as default values. The schema is actually read, it does not validate anything but it loads the default values if any are set and then merges those with env.php values.
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
> NOTE: After adding a new Method or route you need to regenerate routes by cli command bin/console generate:routes  . This will create routes.php file in config/generated folder.


The route is matched by frontController. All the routes in Controller directory are managed by defaultRouter, if needed new Routers Can be created (API specific, robots.txt , etc), but this functionality was mainly added for additional training complexity.

## Response
To render a page an object extending HttpAbstractResponse or implementing HttpResponseInterface must be used, Depending on the response type ```content``` value must be set before calling ```$response->send()```.

For regular page rendering ```PageRenderer``` object must be passed as content (ViewInterface is expected). The constructor for this object takes the location of the phtml template relative to ```src/View``` directory without '.phml' this template is gonna be inserted inside of Base scaffolding template which is located in ```src/View/Base/index.phtml```. Option second parameter can be passed if different Base template is preferred.

For JSON response use JsonRenderer and HttpJsonResponse instances respectively. Example can be found ir ```src/Controller/Json/JsonTestData.php```

## ViewInterface
To include one template in another ```$this->include()``` can be used directly inside a template specifying the template file and if necessary data that can be used in the included template, data can be either DataCollection instance or an array , but it will be converted to an instance of DataCollection regardless. It can be accesses through ```$data``` variable from the included template.
DataObject uses magic __call method to store and get keys for example if you pass an array ['firsName' => 'john', 'lastName' => 'doe'] in the template file this can be used as ```$data->getFirstName(); $data->getLastName();``` The scope is isolated by using Closures so if you do not explicitly send data from
parent template to the child template, it won't be available.

```php
<?php /** @var \Core\Models\DataObject $data */ ?>
<header class="w-full bg-blue-600 text-white py-16 px-4 md:px-8 text-center">
    <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to the Home Page</h1>
    <p class="text-lg md:text-xl max-w-2xl mx-auto">
        This is a simple, clean layout using Tailwind CSS for a modern homepage look.
    </p>
</header>
<?= $this->include('Navigation/nav', $data)?>
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

### Old Post Data
In case of redirect, if redirect has been called with 'withPostData' method - post values from previous request are stored in session flash and can be accesed in templates by calling ``` $data->getOld{$name}()```
```php
<div>
    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
    <input type="text" name="title" id="title" required
           value="<?= $data->getOldTitle() ?: "" ?>"
           class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-50 px-4 py-2 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition">
</div>

<div>
    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
    <textarea name="message" id="message" rows="6" required
              class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-50
               px-4 py-2 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-200
                focus:ring-opacity-50 transition"><?= $data->getOldMessage() ?: "" ?></textarea>
</div>
```
## Redirect
Redirect can be called on Response instance, helper functions can be attached to either redirect with flash message or with current post data,
note that redirect does not terminate the code flow so you have to make sure nothing else is called afterward yourself (if not intended).
```php
private function handleCsrfException(CsrfException $e): void
{
    $response = new HttpResponse();
    $redirectUrl = $this->request->getReferer() ?: $this->request->getHost();
    $response->withMessage(MessageType::ERROR, $e->getMessage())->redirect($redirectUrl)->withPostData();
}
```
### Flash Messages
A message that will be set only for the current response can be attached using withMessage() function.
To see the passed message on your response page you can either include the default message template in your phtml file or
create your own message template.
```php
<?= $this->include('Partial/messagebox') ?>
```
> Note: Flash message is consumed inside PageRenderer constructor so if no PageRenderer is invoked the message stays in session.

All messages are wrapped inside of ```Core\View\Widgets\Message``` class which can be directly accessed in any template by
```$data->getMessages()```
## Dependency Injection and Interface Binding

You can add any Class to Controller method parameters it will be automatically injected. The same goes for controller constructor. 
```php
#[Route('/about')]
class About
{
    #[GET]
    public function get(HttpRequest $request, Session $userSession): void
    {
        $view = new PageRenderer('About/about');
        $view->setData(new DataObject(['sessionText' => $userSession->get('test_key')]));
        $view->setTitle("About us");
        $response = new HttpResponse($view);
        $response->send();
    }

}

```
By Default All Bindings are shared meaning that
it will return a singleton interface, this is not the case for 'method specific' binding but only if the object has not been bound yet.
For controller method binding the sames goes also for recursive bounds. If you want all the DIs in controller to be singleton then use constructor binding instead.

Instances can be created and bound through the `app()` function, available anywhere in the app.

- To get an initialized instance, use `app()->make()` or `app()->get()` (`get()` is an alias for `make()`).
- By default, `make()` and `get()` return **singleton instances**.
- To get a non-shared instance, use `app()->makeTransient()`.
  > Note: `makeTransient()` will still return a stored singleton if `make()` was previously called for the same class.


If you want to DI an interface it needs to be bound to a class first, Binding can be done inside of config ``` config/bindings.php ``` You can also do contextual binding as in this exampel from one of test cases
```PHP
    public function testContextualBindingWorks()
    {
        app()->bind(Shape::class, Triangle::class);
        app()->bindWithContext(Shape::class, Triangle::class, Polygon1::class);
        app()->bindWithContext(Shape::class, Rectangle::class, Polygon2::class);

        $polygon1 = app()->get(Polygon1::class);
        $polygon2 = app()->get(Polygon2::class);
        $polygon3 = app()->get(Polygon3::class);

        $this->assertStringContainsString("Triangle", $polygon1->describe());
        $this->assertStringContainsString("Rectangle", $polygon2->describe());
        $this->assertStringContainsString("Triangle", $polygon3->describe());
        $this->assertNotSame($polygon1, $polygon3);
    }
}


```

## Csrf Validation
  By default csrf validation is enabled in env.schema.php and can be disabled by setting it to false in env.php
``'csrf_enabled' => false,``. When Csrf validation is enabled all POST requests will be validated for a csrf token presence, if the token is not present or is invalid the app will redirect to HTTP_REFERER with old post data and a corresponding flash message.
The token can be injected in any template form like this 
```
 <form action="/contactus" method="POST" class="space-y-6">
                    <?= $data->getCsrf() ?>
                    ...
```
This adds an input field with the newest generated csrf token.

### Token Logic
Generated token is valid for specified max time which by default is 48h once the token is consumed (validated as correct) a new token will be generated but the old token will be preserved for up to 1h in case user has multiple tabs open with the same token. At any time max tokens created in session are 3 so already consumed still valid tokens will be removed if user has generated 3 new tokens.

## Session
PHP Sessions can be accessed using ```Core\User\Session``` wrapper, currently it is possible to store the session either with filesystem or redis, this can be configured in env.php and by default it is filesystem.
>Note: For Redis Session storage you need php-redis extension otherwise depending on php configurations it might silently fall back to filesystem.

It is recommended to use ``Core\Contracts\Session\SessionStorageInterface`` when acquiring or Dependency Injecting the session where needed.
As an example when not using DI directly.
```php
    $session = app()->make(\Core\Contracts\Session\SessionStorageInterface::class);
```
The signatures are as follows 
```php
    public function set(string $key, mixed $value): mixed;

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * @param string $key
     * @return mixed
     */
    public function remove(string $key): mixed;
```
For Config class directly there are also 
```php
public function addFlash($key, array | string $data, bool $replace = true): void
public function getFlash(): array
```

These are for flash messages, if you wish to create any directly, without existing wrappers.
## Styling
The framework uses vite and tailwind, if you add any new tailwind classes that havent been used yet in the project you have to run ```npm run build:css``` this will generate a css file in config/generated/assets. The generated css name contains hash which is diffeent each time and is inserted into the app using ```ViewHelper::getMainStylesheet()``` static method which gets the fileName from the vite manifest.json file, so need to be careful with caching.

## CLI tool

You can add your own commands by specifying the command and class that processes it inside of ``` Cli/Commands.php ``` the class must implement CommandInterface

## phppstan
The app is compatible with phpstan level 5
