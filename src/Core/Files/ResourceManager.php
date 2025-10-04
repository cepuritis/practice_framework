<?php

namespace Core\Files;

class ResourceManager
{
    private string $assetDir;
    private array $loadedIcons = [];
    public function __construct(string $assetDirectory = ASSETS_PATH)
    {
        $this->assetDir = $assetDirectory;
    }

    public function getSvgIcon(string $filename)
    {
        $file = $this->assetDir . "/icons/$filename";

        if (!isset($this->loadedIcons[$file])) {
            $this->loadedIcons[$file] = file_get_contents($file);
        }

        return $this->loadedIcons[$file];
    }
}
