<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterSubscription extends BaseModel
{
    protected $table = 'pro_network_newsletter_subscriptions';

    protected $fillable = [
        'user_id',
        'email',
        'subscribed',
        'source',
        'locale',
    ];

    protected $casts = [
        'subscribed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
