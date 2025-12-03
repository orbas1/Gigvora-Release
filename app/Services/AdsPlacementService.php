<?php

namespace App\Services;

use Advertisement\Models\Campaign;
use Advertisement\Models\Creative;
use Advertisement\Models\TargetingRule;
use Advertisement\Services\BidStrategyService;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdsPlacementService
{
    public function __construct(
        protected BidStrategyService $bidStrategy,
        protected KeywordRegistryService $keywordRegistry
    ) {
    }

    public function placementsForContext(array $context, ?User $viewer = null): Collection
    {
        $keywords = $this->keywordRegistry
            ->relatedKeywords($context['keywords'] ?? (($context['query'] ?? null) ? [$context['query']] : []))
            ->pluck('keyword')
            ->map(fn ($keyword) => Str::lower($keyword))
            ->unique();

        $country = $context['country'] ?? $viewer?->address;

        $campaigns = Campaign::query()
            ->with(['creatives', 'targetingRules'])
            ->where('status', 'active')
            ->where('approval_state', 'approved')
            ->where(function ($query) {
                $query->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->get();

        return $campaigns
            ->map(function (Campaign $campaign) use ($keywords, $country) {
                $keywordScore = $this->keywordMatchScore($campaign, $keywords);
                $countryScore = $this->countryMatchScore($campaign, $country);
                $bidScore = $this->bidScore($campaign, $keywords->first());
                $score = $keywordScore + $countryScore + $bidScore;

                $creative = $campaign->creatives->first(function (Creative $creative) {
                    return $creative->status === 'active';
                }) ?? $campaign->creatives->first();

                if (! $creative || $score <= 0) {
                    return null;
                }

                return [
                    'campaign_id' => $campaign->id,
                    'creative_id' => $creative->id,
                    'title' => $creative->title,
                    'body' => $creative->body,
                    'media_path' => $creative->media_path,
                    'cta' => $creative->cta,
                    'destination_url' => $creative->destination_url,
                    'score' => $score,
                ];
            })
            ->filter()
            ->sortByDesc('score')
            ->values();
    }

    protected function keywordMatchScore(Campaign $campaign, Collection $keywords): float
    {
        $rules = $campaign->targetingRules->where('type', 'keyword');

        if ($rules->isEmpty() || $keywords->isEmpty()) {
            return $keywords->isEmpty() ? 0 : 0.5;
        }

        $matches = $rules->filter(function (TargetingRule $rule) use ($keywords) {
            return $keywords->contains(Str::lower($rule->value));
        });

        return $matches->count() / max($rules->count(), 1);
    }

    protected function countryMatchScore(Campaign $campaign, ?string $country): float
    {
        if (! $country) {
            return 0.2;
        }

        $countryRule = $campaign->targetingRules->firstWhere('type', 'country');

        if (! $countryRule) {
            return 0.4;
        }

        return Str::contains(Str::lower($country), Str::lower($countryRule->value)) ? 1 : 0;
    }

    protected function bidScore(Campaign $campaign, ?string $keyword): float
    {
        if (! $keyword) {
            return 0.2;
        }

        $priced = $this->bidStrategy->priceKeyword($keyword);
        $bidType = Str::lower($campaign->bidding ?? 'cpc');

        return match ($bidType) {
            'cpa' => (float) $priced->cpa,
            'cpm' => (float) $priced->cpm,
            default => (float) $priced->cpc,
        };
    }
}
