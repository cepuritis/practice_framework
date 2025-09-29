<?php

namespace Core\Contracts\View;

enum MessageType: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case INFO = 'info';
}
