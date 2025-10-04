<?php
namespace Core\Contracts\Http;
use Core\Exceptions\CsrfInvalidException;
use Core\Exceptions\CsrfMissingException;

interface HttpRequestInterface
{
    public const METHOD = 'METHOD';
    public const SCHEME = 'SCHEME';
    public const HOST = 'HOST';
    public const PATH = 'PATH';
    public const PARAMS = 'PARAMS';
    public const POST_DATA = 'POST_DATA';
    public const REFERER = 'HTTP_REFERER';
    public function getMethod(): HttpRequestMethod;

    public function getScheme(): string;

    public function getPath(): string;

    public function getHost(): string;

    /**
     * @return array<string, string>
     */
    public function getParams(): array;

    /**
     * @return array<string, mixed>
     */
    public function getPostData(): array;

    public function getPostParam(string $name): ?string;

//    public function redirect(string $path, HttpRequestMethod $method = HttpRequestMethod::GET): void;

    public function getReferer(): string;

}
