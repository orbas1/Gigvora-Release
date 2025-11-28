<?php

namespace ProNetwork\Services;

use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use ProNetwork\Support\Notifications\WrappedNotification;

class NotificationsWrapper
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function send(Model $notifiable, string $type, array $payload = [], array $channels = ['database']): void
    {
        $this->dispatcher->send($notifiable, new WrappedNotification($type, $payload, $channels));
    }

    public function sendToMany(iterable $notifiables, string $type, array $payload = [], array $channels = ['database']): void
    {
        foreach ($notifiables as $notifiable) {
            $this->send($notifiable, $type, $payload, $channels);
        }
    }
}
