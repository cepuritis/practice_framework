<?php

namespace Core\View\Traits;

use Core\Contracts\Session\SessionStorageInterface;
use Core\View\Widgets\Message;

trait FlashMessageRenderer
{
    private function addFlashMessagesToData(): void
    {
        $session = app()->get(SessionStorageInterface::class);
        $messages = [];
        if (isset($session->getFlash()['message'])) {
            $messageData = $session->getFlash()['message'];
            $messageData = isset($messageData[0]) ? $messageData : [$messageData];
            foreach ($messageData as $message) {
                foreach ($message as $status => $text) {
                    $messages[] = new Message($status, $text);
                }
            }
        }
        $this->data->setMessages($messages);
    }
}