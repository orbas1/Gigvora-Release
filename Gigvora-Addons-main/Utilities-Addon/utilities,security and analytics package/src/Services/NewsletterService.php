<?php

namespace ProNetwork\Services;

use ProNetwork\Models\NewsletterSubscription;

class NewsletterService
{
    public function subscribe(string $email, ?int $userId = null, ?string $source = null): NewsletterSubscription
    {
        return NewsletterSubscription::updateOrCreate([
            'email' => $email,
        ], [
            'user_id' => $userId,
            'subscribed' => true,
            'source' => $source,
        ]);
    }

    public function unsubscribe(string $email): void
    {
        NewsletterSubscription::where('email', $email)->update(['subscribed' => false]);
    }
}
