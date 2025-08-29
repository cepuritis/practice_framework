<?php
namespace Core;

use Core\Contracts\HttpRequestInterface;
use Core\Contracts\HttpRequestMethod;
class HttpRequest implements HttpRequestInterface
{
    private array $request = [];
    private static ?HttpRequest $instance = null;

    private function __construct()
    {
        $this->request[self::METHOD] = HttpRequestMethod::fromString($_SERVER['REQUEST_METHOD']);
        $this->request[self::SCHEME] = $_SERVER['REQUEST_SCHEME'];
        $this->request[self::HOST] = $_SERVER['HTTP_HOST'];
        $this->request[self::PATH] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $params = [];
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? "", $params);
        $this->request[self::PARAMS] = $params;
    }

    /**
     * @return HttpRequestInterface
     */
    public static function getInstance(): HttpRequestInterface
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return  self::$instance;
    }

    /**
     * @return string
     */
    public function getMethod(): HttpRequestMethod
    {
        return $this->request[self::METHOD];
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->request[self::SCHEME];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->request[self::PATH];
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->request[self::HOST];
    }

    /**
     * @return array<string, string>
     */
    public function getParams(): array
    {
        return $this->request[self::PARAMS];
    }
}
