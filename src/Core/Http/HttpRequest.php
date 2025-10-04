<?php
namespace Core\Http;

use Core\Contracts\Config\ConfigInterface;
use Core\Contracts\Http\HttpRequestInterface;
use Core\Contracts\Http\HttpRequestMethod;
use Core\Exceptions\CsrfInvalidException;
use Core\Exceptions\CsrfMissingException;
use Core\Exceptions\InvalidHttpMethod;
use Core\Security\CsrfTokenManager;

class HttpRequest implements HttpRequestInterface
{
    private array $request = [];
    private ConfigInterface $config;
    /**
     * @throws InvalidHttpMethod
     */
    public function __construct(ConfigInterface $config)
    {
        $this->request[self::METHOD] = HttpRequestMethod::fromString(
            $_SERVER['REQUEST_METHOD'] ?? HttpRequestMethod::GET->value
        );
        $this->request[self::SCHEME] = $_SERVER['REQUEST_SCHEME'];
        $this->request[self::HOST] = $_SERVER['HTTP_HOST'];
        $this->request[self::PATH] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $params = [];
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? "", $params);
        $this->request[self::PARAMS] = $params;
        $this->request[self::POST_DATA] = $_POST ?? [];
        $this->request[self::REFERER] = $_SERVER[self::REFERER] ?? "";
        $this->config = $config;
    }

    /**
     * @throws CsrfInvalidException
     * @throws CsrfMissingException
     * @return void
     */

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

    /**
     * @return array
     */
    public function getPostData(): array
    {
        return $this->request[self::POST_DATA];
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getPostParam(string $name): ?string
    {
        return $this->request[self::POST_DATA][$name] ?? null;
    }

    public function redirect(string $path, HttpRequestMethod $method = HttpRequestMethod::GET)
    {
        $this->request[self::PATH] = $path;
        $this->request[self::METHOD] = $method;
    }

    /**
     * @return string
     */
    public function getReferer(): string
    {
        return $this->request[self::REFERER];
    }
}
