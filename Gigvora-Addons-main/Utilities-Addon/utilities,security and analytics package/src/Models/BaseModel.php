<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected function userClass(): string
    {
        return config('pro_network_utilities_security_analytics.models.user', config('auth.providers.users.model', \App\Models\User::class));
    }

    protected function postClass(): string
    {
        return config('pro_network_utilities_security_analytics.models.post', \App\Models\Post::class);
    }

    protected function pageClass(): string
    {
        return config('pro_network_utilities_security_analytics.models.page', \App\Models\Page::class);
    }

    protected function groupClass(): string
    {
        return config('pro_network_utilities_security_analytics.models.group', \App\Models\Group::class);
    }

    protected function storyClass(): string
    {
        return config('pro_network_utilities_security_analytics.models.story', \App\Models\Story::class);
    }

    protected function orderClass(): string
    {
        return config('pro_network_utilities_security_analytics.models.marketplace_order', \App\Models\MarketplaceOrder::class);
    }
}
