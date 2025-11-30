<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Follower;
use App\Models\Friendships;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Support\Carbon;

class GigvoraVerifyService
{
    public function buildReport(User $user): array
    {
        $followers = Follower::where('follow_id', $user->id)->count();
        $connections = Friendships::where('is_accepted', 1)
            ->where(function ($query) use ($user) {
                $query->where('accepter', $user->id)
                    ->orWhere('requester', $user->id);
            })
            ->count();
        $likes = $this->countReactionsFor($user);
        $accountAge = $user->created_at ? Carbon::parse($user->created_at)->diffInDays(now()) : 0;

        $rules = config('gigvora_verify.eligibility', []);

        $meetsAudience = $followers >= ($rules['followers'] ?? 0) || $connections >= ($rules['connects'] ?? 0);
        $meetsLikes = $likes >= ($rules['likes'] ?? 0);
        $meetsAge = $accountAge >= ($rules['account_age_days'] ?? 0);

        $requirements = [
            [
                'key' => 'audience',
                'label' => __('Community size'),
                'expected' => __('2,500 followers or 1,000 connects'),
                'met' => $meetsAudience,
            ],
            [
                'key' => 'likes',
                'label' => __('Engagement'),
                'expected' => __('5,000 lifetime likes'),
                'met' => $meetsLikes,
            ],
            [
                'key' => 'age',
                'label' => __('Account age'),
                'expected' => __('60+ days on Gigvora'),
                'met' => $meetsAge,
            ],
        ];

        $badge = Badge::where('user_id', $user->id)->first();

        return [
            'eligible' => $meetsAudience && $meetsLikes && $meetsAge,
            'counts' => [
                'followers' => $followers,
                'connections' => $connections,
                'likes' => $likes,
                'account_age_days' => $accountAge,
            ],
            'requirements' => $requirements,
            'badge' => $badge,
        ];
    }

    protected function countReactionsFor(User $user): int
    {
        $total = 0;

        Posts::where('user_id', $user->id)
            ->select(['post_id', 'user_reacts'])
            ->chunkById(250, function ($posts) use (&$total) {
                foreach ($posts as $post) {
                    $reactions = json_decode($post->user_reacts ?? '[]', true);

                    if (is_array($reactions)) {
                        $total += count($reactions);
                    }
                }
            });

        return $total;
    }
}

