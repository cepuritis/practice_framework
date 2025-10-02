<?php

namespace Core\View\Widgets;

use Core\Files\ResourceManager;

class Message
{
    public const SUCCESS = 'success';
    public const ERROR = 'error';
    public const INFO = 'info';

    private string $status;

    private string $text;
    public function __construct(
        string $status,
        string $text
    ) {
        $this->status = strtolower($status);
        $this->text = $text;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCssClasses(): string
    {
        return match ($this->status) {
            self::SUCCESS => "bg-green-50 border border-green-200 text-green-800",
            self::ERROR   => "bg-red-50 border border-red-200 text-red-800",
            default => "bg-blue-50 border border-blue-200 text-blue-800",
        };
    }

    public function getIcon(): string
    {
        /**
         * @var ResourceManager $resourceManager
         */
        $resourceManager = app()->make(ResourceManager::class);
        return match ($this->status) {
            self::SUCCESS => $resourceManager->getSvgIcon('success.svg'),
            self::ERROR   => $resourceManager->getSvgIcon('error.svg'),
            default       => $resourceManager->getSvgIcon('info.svg'),
        };
    }

    public function render(): string
    {
        return sprintf(
            '<div class="mt-4 rounded-lg %s p-4 shadow-sm">
                <div class="flex items-center">%s
                    <p class="ml-3 text-sm font-medium">%s</p>
                </div>
            </div>',
            $this->getCssClasses(),
            $this->getIcon(),
            htmlspecialchars($this->text, ENT_QUOTES, 'UTF-8')
        );
    }
}
