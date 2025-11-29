<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Services;

use App\Models\User;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiByokCredential;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiSession;
use Gigvora\TalentAi\Domain\Shared\Enums\AiSessionStatus;
use Gigvora\TalentAi\Domain\Shared\Events\AiSessionCompleted;
use Illuminate\Validation\ValidationException;

class AiWorkspaceService
{
    public function __construct(private AiProviderService $provider, private AiBillingService $billing)
    {
    }

    public function runTool(string $tool, User $user, array $payload): AiSession
    {
        $this->enforceGuardrails($payload);

        if (!$this->billing->checkLimits($user)) {
            throw ValidationException::withMessages(['usage' => 'Usage limit reached for your plan.']);
        }

        $session = AiSession::create([
            'user_id' => $user->id,
            'tool' => $tool,
            'status' => AiSessionStatus::Running,
            'input' => $payload,
        ]);

        $credential = $this->resolveCredential($user);
        $output = $this->provider->callProvider($tool, $payload, $user, $credential);

        $session->status = AiSessionStatus::Completed;
        $session->output = $output;
        $session->prompt_tokens = $payload['prompt_tokens'] ?? 0;
        $session->completion_tokens = $output['tokens'] ?? 0;
        $session->credit_cost = $this->calculateCost($session);
        $session->save();

        $this->billing->recordUsage($session);
        AiSessionCompleted::dispatch($session);

        return $session;
    }

    public function cvWriter(User $user, array $payload): AiSession
    {
        $this->validateFields($payload, ['profile_summary']);
        return $this->runTool('cv_writer', $user, $payload);
    }

    public function outreachComposer(User $user, array $payload): AiSession
    {
        $this->validateFields($payload, ['audience', 'tone']);
        return $this->runTool('outreach', $user, $payload);
    }

    public function socialCalendar(User $user, array $payload): AiSession
    {
        $this->validateFields($payload, ['niche']);
        return $this->runTool('social_calendar', $user, $payload);
    }

    public function careerCoach(User $user, array $payload): AiSession
    {
        $this->validateFields($payload, ['question']);
        return $this->runTool('career_coach', $user, $payload);
    }

    public function repurpose(User $user, array $payload): AiSession
    {
        $this->validateFields($payload, ['content']);
        return $this->runTool('repurpose', $user, $payload);
    }

    public function interviewPrep(User $user, array $payload): AiSession
    {
        $this->validateFields($payload, ['role']);
        return $this->runTool('interview_prep', $user, $payload);
    }

    public function imageCanvas(User $user, array $payload): AiSession
    {
        $this->validateFields($payload, ['prompt']);
        return $this->runTool('image_canvas', $user, $payload);
    }

    public function writer(User $user, array $payload): AiSession
    {
        $this->validateFields($payload, ['topic']);
        return $this->runTool('writer', $user, $payload);
    }

    public function marketingBot(User $user, array $payload): AiSession
    {
        $this->validateFields($payload, ['product']);
        return $this->runTool('marketing_bot', $user, $payload);
    }

    protected function validateFields(array $payload, array $fields): void
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $payload) || empty($payload[$field])) {
                throw ValidationException::withMessages([$field => 'Field is required']);
            }
        }
    }

    protected function enforceGuardrails(array $payload): void
    {
        $maxPrompt = config('gigvora_talent_ai.ai.guardrails.max_prompt_length', 4000);
        if (isset($payload['prompt']) && strlen($payload['prompt']) > $maxPrompt) {
            throw ValidationException::withMessages(['prompt' => 'Prompt too long']);
        }
    }

    protected function resolveCredential(User $user): ?AiByokCredential
    {
        if (!config('gigvora_talent_ai.ai.byok.enabled')) {
            return null;
        }

        return AiByokCredential::where('user_id', $user->id)->first();
    }

    protected function calculateCost(AiSession $session): int
    {
        $perToken = config('gigvora_talent_ai.ai.cost_model.per_token_usd', 0.0);
        $tokens = $session->prompt_tokens + $session->completion_tokens;
        return (int) round($tokens * $perToken * 1000000); // micro pricing units
    }
}
