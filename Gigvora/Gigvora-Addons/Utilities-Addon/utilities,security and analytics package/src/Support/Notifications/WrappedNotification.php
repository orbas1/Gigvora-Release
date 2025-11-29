<?php

namespace ProNetwork\Support\Notifications;

use Illuminate\Notifications\Notification;

class WrappedNotification extends Notification
{
    public function __construct(
        protected readonly string $type,
        protected readonly array $data = [],
        protected readonly array $channels = ['database']
    ) {
    }

    public function via($notifiable): array
    {
        return $this->channels;
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
        ];
    }
}
