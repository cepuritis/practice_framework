<?php

namespace Core\Helpers;

use Core\Exceptions\ViteManifestException;
use Core\Models\DataObject;
use Core\View\PageRenderer;
use Core\View\ViewRenderer;
use RuntimeException;

class ViewHelper
{
    const STYLE_MAIN_VIEW = 'src/View/assets/css/app.css';
    private static function getViteManifestData(): array
    {
        $path = CONFIG_PATH . '/generated/viteManifest.json';

        $data = json_decode(file_get_contents($path), true);

        if (!$data) {
            throw new ViteManifestException("Vite Manifest Not Found");
        }

        return $data;
    }

    public static function getMainStylesheet()
    {
        $data = self::getViteManifestData();

        if (!isset($data[self::STYLE_MAIN_VIEW])) {
            throw new ViteManifestException("Main Stylesheet not found in Vite manifest");
        }

        return '/build/' . $data[self::STYLE_MAIN_VIEW]['file'];
    }

    public static function include(string $template, DataObject|array $data = [])
    {
        if (!($data instanceof DataObject)) {
            $data = new DataObject($data);
        }
        $view = new ViewRenderer($template, $data);
        PageRenderer::$current->addView($view);

        return $view->render($data);
    }
}
