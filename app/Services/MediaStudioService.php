<?php

namespace App\Services;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Exception\RuntimeException as FFMpegRuntimeException;
use FFMpeg\FFMpeg;
use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class MediaStudioService
{
    public function sanitizeManifest($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                $value = [];
            }
        }

        if (!is_array($value)) {
            return [];
        }

        return [
            'filter' => Arr::get($value, 'filter', 'none'),
            'overlays' => $this->sanitizeOverlays(Arr::get($value, 'overlays', [])),
            'music' => Arr::get($value, 'music'),
            'crop' => Arr::only(Arr::get($value, 'crop', []), ['aspect']),
            'gifs' => $this->sanitizeKeys(Arr::get($value, 'gifs', []), array_keys(config('media_studio.gifs', []))),
            'stickers' => $this->sanitizeKeys(Arr::get($value, 'stickers', []), array_keys(config('media_studio.stickers', []))),
        ];
    }

    public function sanitizeResolution(?string $preset): string
    {
        $preset = strtolower($preset ?? 'auto');
        return array_key_exists($preset, config('media_studio.resolutions')) ? $preset : 'auto';
    }

    public function ensureResolution(string $absolutePath, string $fileType, string $preset): ?Dimension
    {
        $definitions = config('media_studio.resolutions');
        if ($preset === 'auto' || !isset($definitions[$preset])) {
            return null;
        }

        $target = $definitions[$preset];

        if ($fileType === 'image' && $target['width'] && $target['height']) {
            $image = Image::make($absolutePath)->orientate();
            $image->resize($target['width'], $target['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($absolutePath);

            return new Dimension($image->width(), $image->height());
        }

        if ($fileType === 'video' && $target['width'] && $target['height']) {
            try {
                $ffmpeg = FFMpeg::create();
                $video = $ffmpeg->open($absolutePath);
                $dimension = new Dimension($target['width'], $target['height']);

                $format = new X264();
                $format->setAudioKiloBitrate(192);
                $format->setKiloBitrate(4500);

                $tempPath = $absolutePath.'.tmp.mp4';

                $video->filters()->resize($dimension, ResizeFilter::RESIZEMODE_INSET, true);
                $video->save($format, $tempPath);

                rename($tempPath, $absolutePath);

                return $dimension;
            } catch (FFMpegRuntimeException $exception) {
                Log::warning('MediaStudio: unable to transcode video', [
                    'path' => $absolutePath,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return null;
    }

    public function applyFilter(string $absolutePath, string $fileType, string $filterKey): void
    {
        $filters = config('media_studio.filters');
        if (!isset($filters[$filterKey]) || $filterKey === 'none') {
            return;
        }

        if ($fileType !== 'image') {
            // Video filters are handled in the player layer for now.
            return;
        }

        $image = Image::make($absolutePath)->orientate();

        switch ($filterKey) {
            case 'vivid':
                $image->contrast(8)->brightness(3)->colorize(2, 2, 0);
                break;
            case 'noir':
                $image->greyscale()->contrast(15);
                break;
            case 'dusk':
                $image->colorize(10, -5, 18)->brightness(5);
                break;
            default:
                break;
        }

        $image->save($absolutePath);
    }

    public function sanitizeProcessingMeta(?array $manifest, string $preset, ?Dimension $dimension): array
    {
        $filter = Arr::get($manifest, 'filter', 'none');

        return [
            'filter' => $filter,
            'overlays' => Arr::get($manifest, 'overlays', []),
            'music' => Arr::get($manifest, 'music'),
            'crop' => Arr::get($manifest, 'crop'),
            'stickers' => Arr::get($manifest, 'stickers', []),
            'gifs' => Arr::get($manifest, 'gifs', []),
            'resolution' => $preset,
            'dimension' => $dimension ? [
                'width' => $dimension->getWidth(),
                'height' => $dimension->getHeight(),
            ] : null,
        ];
    }

    protected function sanitizeOverlays($overlays): array
    {
        if (!is_array($overlays)) {
            return [];
        }

        return collect($overlays)
            ->take(5)
            ->map(function ($overlay) {
                return [
                    'type' => in_array(Arr::get($overlay, 'type'), ['text', 'emoji', 'sticker']) ? $overlay['type'] : 'text',
                    'value' => (string) Arr::get($overlay, 'value', ''),
                    'x' => (float) Arr::get($overlay, 'x', 0.5),
                    'y' => (float) Arr::get($overlay, 'y', 0.5),
                    'color' => Arr::get($overlay, 'color', '#ffffff'),
                    'size' => (int) Arr::get($overlay, 'size', 18),
                ];
            })
            ->values()
            ->toArray();
    }

    protected function sanitizeKeys($values, array $allowed): array
    {
        if (!is_array($values)) {
            return [];
        }

        return array_values(array_intersect($allowed, $values));
    }
}

