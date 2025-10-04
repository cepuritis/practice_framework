<?php

namespace Core\View\Traits;

use Core\Contracts\Session\SessionStorageInterface;
use Core\Models\Data\DataCollection;

trait UseOldPostData
{
    /**
     * @param DataCollection $data
     * @return void
     */
    private function setOldPostData(DataCollection &$data)
    {
        $session = app()->make(SessionStorageInterface::class);
        if (isset($session->getFlash()['oldPostData'])) {
            $old = $session->getFlash()['oldPostData'];
            foreach ($old as $key => $value) {
                $propertyKey = 'old'. ucfirst($key);
                $data[$propertyKey] = $value;
            }
        }
    }
}
