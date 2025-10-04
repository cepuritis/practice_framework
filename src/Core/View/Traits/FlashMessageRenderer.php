<?php

namespace Core\View\Traits;

use Core\User\Session;
use Core\View\Widgets\Message;

trait FlashMessageRenderer
{
    private function addFlashMessagesToData(): void
    {
        $session = app()->get(Session::class);
        $messages = [];
        if (isset($session->getFlash()['message'])) {
            $messageData = $session->getFlash()['message'];
            foreach ($messageData as $message) {
                foreach ($message as $status => $text) {
                    $messages[] = new Message($status, $text);
                }
            }
        }
        $this->data->setMessages($messages);
    }
}