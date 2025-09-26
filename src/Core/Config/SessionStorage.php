<?php

namespace Core\Config;

use Core\Helpers\Traits\EnumSupportedValues;

enum SessionStorage: string
{
    use EnumSupportedValues;
    case FILES = 'files';
    case REDIS = 'redis';
    case MEMCACHED = 'memcached';

}