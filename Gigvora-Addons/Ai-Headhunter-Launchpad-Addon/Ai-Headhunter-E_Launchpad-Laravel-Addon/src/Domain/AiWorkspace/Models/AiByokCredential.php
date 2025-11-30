<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class AiByokCredential extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'api_key',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    protected $hidden = [
        'api_key',
    ];

    protected $appends = [
        'key_suffix',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User');
    }

    protected function apiKey(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): ?string {
                if (empty($value)) {
                    return null;
                }

                try {
                    return Crypt::decryptString($value);
                } catch (Throwable $exception) {
                    return $value;
                }
            },
            set: function (?string $value): ?string {
                return $value ? Crypt::encryptString($value) : null;
            }
        );
    }

    protected function keySuffix(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $stored = $this->attributes['api_key'] ?? null;
                if (! $stored) {
                    return null;
                }

                try {
                    $plain = Crypt::decryptString($stored);
                } catch (Throwable $exception) {
                    $plain = $stored;
                }

                return substr($plain, -4);
            }
        );
    }
}
