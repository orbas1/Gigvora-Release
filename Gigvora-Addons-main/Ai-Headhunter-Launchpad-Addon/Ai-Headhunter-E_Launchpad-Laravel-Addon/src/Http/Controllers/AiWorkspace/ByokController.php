<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\AiWorkspace;

use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiByokCredential;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ByokController extends Controller
{
    protected function ensureEnabled(): void
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.ai_workspace.enabled') && config('gigvora_talent_ai.ai.byok.enabled'), 403);
    }

    public function store(Request $request): JsonResponse
    {
        $this->ensureEnabled();
        $data = $request->validate([
            'provider' => ['required', 'string'],
            'api_key' => ['required', 'string'],
            'meta' => ['array'],
        ]);

        $credential = AiByokCredential::updateOrCreate(
            ['user_id' => $request->user()->id, 'provider' => $data['provider']],
            ['api_key' => $data['api_key'], 'meta' => $data['meta'] ?? []]
        );

        return response()->json($credential);
    }

    public function destroy(Request $request, AiByokCredential $credential): JsonResponse
    {
        $this->ensureEnabled();
        abort_unless($credential->user_id === $request->user()->id, 403);
        $credential->delete();

        return response()->json(['deleted' => true]);
    }
}
