<?php

namespace App\Http\Controllers;

use App\Services\UtilitiesComposerAssetsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UtilitiesComposerController extends Controller
{
    public function __construct(protected UtilitiesComposerAssetsService $assets)
    {
    }

    public function assets(Request $request): JsonResponse
    {
        $payload = $this->assets->payload();

        return response()->json($payload);
    }

    public function gifs(Request $request): JsonResponse
    {
        $config = config('utilities.composer.gif');
        $enabled = (bool) ($config['enabled'] ?? false);
        $apiKey = $config['api_key'] ?? null;

        abort_unless($enabled && ! empty($apiKey), 404);

        $query = trim((string) $request->input('q', 'celebration'));
        $limit = (int) ($config['limit'] ?? 12);
        $limit = max(1, min($limit, 25));

        $response = Http::timeout(6)->get($config['endpoint'], [
            'q' => $query === '' ? 'celebration' : $query,
            'key' => $apiKey,
            'client_key' => config('app.name', 'gigvora'),
            'limit' => $limit,
            'media_filter' => 'minimal',
        ]);

        if ($response->failed()) {
            return response()->json([
                'data' => [],
                'error' => 'unavailable',
            ], 503);
        }

        $data = collect($response->json('results', []))
            ->map(function ($item) {
                $media = $item['media_formats']['tinygif'] ?? $item['media_formats']['gif'] ?? null;

                if (! $media) {
                    return null;
                }

                return [
                    'id' => $item['id'] ?? null,
                    'title' => $item['content_description'] ?? '',
                    'url' => $media['url'] ?? null,
                    'preview' => $media['preview'] ?? $media['url'] ?? null,
                ];
            })
            ->filter(fn ($gif) => ! empty($gif['url']))
            ->values();

        return response()->json(['data' => $data]);
    }
}

