<?php

namespace Core\Helpers;

use Core\Exceptions\ViteManifestException;
use Core\Models\Data\DataCollection;
use Core\View\PageRenderer;
use Core\View\ViewRenderer;

class ViewHelper
{
    const STYLE_MAIN_VIEW = 'src/View/assets/css/app.css';

    /**
     * @return array
     */
    private static function getViteManifestData(): array
    {
        $path = CONFIG_PATH . '/generated/viteManifest.json';

        $data = json_decode(file_get_contents($path), true);

        if (!$data) {
            throw new ViteManifestException("Vite Manifest Not Found");
        }

        return $data;
    }

    /**
     * @return string
     */
    public static function getMainStylesheet()
    {
        $data = self::getViteManifestData();

        if (!isset($data[self::STYLE_MAIN_VIEW])) {
            throw new ViteManifestException("Main Stylesheet not found in Vite manifest");
        }

        return '/build/' . $data[self::STYLE_MAIN_VIEW]['file'];
    }

    /**
     * @param string $template
     * @param DataCollection|array $data
     * @return string
     * @throws \ReflectionException
     */
    public static function include(string $template, DataCollection|array $data = [])
    {
        if (!($data instanceof DataCollection)) {
            $data = new DataCollection($data);
        }
        $view = new ViewRenderer($template, $data);
        PageRenderer::$current->addView($view);

        return $view->render($data);
    }
}
