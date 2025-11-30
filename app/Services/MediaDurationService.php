<?php

namespace App\Services;

use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Log;

class MediaDurationService
{
    public const REEL_MAX_SECONDS = 120;

    protected FFProbe $ffprobe;

    public function __construct(?FFProbe $ffprobe = null)
    {
        $this->ffprobe = $ffprobe ?? FFProbe::create();
    }

    public function getDurationSeconds(?string $absolutePath): ?int
    {
        if (empty($absolutePath) || !is_file($absolutePath)) {
            return null;
        }

        try {
            $duration = $this->ffprobe->format($absolutePath)->get('duration');
            if ($duration === null) {
                return null;
            }

            return (int) round((float) $duration);
        } catch (\Throwable $exception) {
            Log::warning('Unable to read video duration', [
                'path' => $absolutePath,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    public function isReel(?int $durationSeconds): bool
    {
        return $durationSeconds !== null && $durationSeconds <= self::REEL_MAX_SECONDS;
    }
}

