<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Services;

use App\Models\User;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiByokCredential;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class AiProviderService
{
    public function callProvider(string $toolSlug, array $payload, User $user, ?AiByokCredential $credential = null): array
    {
        $provider = config('gigvora_talent_ai.ai.provider', 'stub');

        return match ($provider) {
            'openai' => $this->callOpenAi($toolSlug, $payload, $credential),
            default => $this->fakeResponse($toolSlug, $payload),
        };
    }

    protected function callOpenAi(string $tool, array $payload, ?AiByokCredential $credential): array
    {
        $apiKey = $this->resolveApiKey($credential, 'openai');
        if (! $apiKey) {
            throw ValidationException::withMessages([
                'provider' => 'OpenAI key is not configured for this workspace.',
            ]);
        }

        $model = config('gigvora_talent_ai.ai.providers.openai.model', 'gpt-4o-mini');

        try {
            $response = Http::withToken($apiKey)
                ->timeout(25)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'temperature' => 0.15,
                    'messages' => [
                        ['role' => 'system', 'content' => $this->systemPrompt($tool)],
                        ['role' => 'user', 'content' => $this->buildPrompt($tool, $payload)],
                    ],
                ])
                ->throw()
                ->json();
        } catch (Throwable $exception) {
            Log::channel(config('gigvora_talent_ai.analytics.log_channels.ai_errors', 'stack'))
                ->error('OpenAI request failed', ['tool' => $tool, 'message' => $exception->getMessage()]);

            throw ValidationException::withMessages([
                'provider' => 'Unable to contact OpenAI right now. Please try again shortly.',
            ]);
        }

        $message = trim((string) data_get($response, 'choices.0.message.content', ''));
        $tokens = (int) data_get($response, 'usage.total_tokens', 0);

        return [
            'provider' => 'openai',
            'tool' => $tool,
            'message' => $message,
            'variants' => [$message],
            'tokens' => $tokens,
            'model' => $model,
        ];
    }

    protected function resolveApiKey(?AiByokCredential $credential, string $provider): ?string
    {
        if ($credential && $credential->provider === $provider && $credential->api_key) {
            return $credential->api_key;
        }

        return config("gigvora_talent_ai.ai.providers.{$provider}.api_key");
    }

    protected function fakeResponse(string $tool, array $payload): array
    {
        $summary = collect($payload)
            ->filter(fn ($value) => is_scalar($value) && !empty($value))
            ->map(fn ($value, $key) => strtoupper(str_replace('_', ' ', (string) $key)).': '.$value)
            ->implode("\n");

        $message = $summary
            ? "Simulated {$tool} output based on:\n".$summary
            : "Simulated {$tool} output.";

        return [
            'provider' => 'stub',
            'tool' => $tool,
            'message' => $message,
            'variants' => [
                $message,
                $message."\n\n".'Variant 2: Expand on the idea with a different tone.',
                $message."\n\n".'Variant 3: Provide a concise summary.',
            ],
            'tokens' => 0,
        ];
    }

    protected function systemPrompt(string $tool): string
    {
        return match ($tool) {
            'cv_writer' => 'You craft concise CV/Resume snippets, bullet points, and executive summaries tailored for LinkedIn-style profiles.',
            'outreach' => 'You write personalised outreach emails and networking notes that feel authentic and respectful.',
            'social_calendar' => 'You design short social content calendars with post ideas, hooks, and CTA suggestions.',
            'career_coach', 'coach' => 'You provide practical career coaching advice grounded in positive language and actionable steps.',
            'repurpose' => 'You transform existing posts into fresh formats (threads, emails, summaries) while keeping the author voice.',
            'interview_prep' => 'You prepare behavioural/technical interview questions and sample answers with STAR framing.',
            'image_canvas' => 'You describe imaginative visual directions that a design or AI art tool could follow.',
            'writer' => 'You draft compelling long-form articles or scripts with strong openings and clear structure.',
            'marketing_bot' => 'You produce concise marketing copy, taglines, and campaign angles for digital channels.',
            default => 'You are a helpful professional assistant for Gigvora users. Respond with polished, friendly business language.',
        };
    }

    protected function buildPrompt(string $tool, array $payload): string
    {
        $lines = [];
        foreach ($payload as $key => $value) {
            if (is_scalar($value) && $value !== '') {
                $label = strtoupper(str_replace('_', ' ', (string) $key));
                $lines[] = "{$label}: {$value}";
            }
        }

        if (empty($lines)) {
            $lines[] = 'Context: No additional parameters were provided.';
        }

        return implode("\n", $lines);
    }
}
