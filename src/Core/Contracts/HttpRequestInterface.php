<?php
namespace Core\Contracts;
interface HttpRequestInterface
{
    public const METHOD = 'METHOD';
    public const SCHEME = 'SCHEME';
    public const HOST = 'HOST';
    public const PATH = 'PATH';
    public const PARAMS = 'PARAMS';
}