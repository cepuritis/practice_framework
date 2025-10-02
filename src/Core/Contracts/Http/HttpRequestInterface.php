<?php
namespace Core\Contracts\Http;
interface HttpRequestInterface
{
    public const METHOD = 'METHOD';
    public const SCHEME = 'SCHEME';
    public const HOST = 'HOST';
    public const PATH = 'PATH';
    public const PARAMS = 'PARAMS';
    public const POST_DATA = 'POST_DATA';
}
