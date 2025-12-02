<?php

namespace App\Support\Authorization;

use App\Models\AuditLog;
use Illuminate\Contracts\Auth\Authenticatable;

class AuditLogger
{
    public function log(?Authenticatable $actor, string $action, ?string $targetType = null, ?int $targetId = null, array $changes = [], string $source = 'web'): AuditLog
    {
        return AuditLog::create([
            'actor_id' => $actor?->getAuthIdentifier(),
            'target_type' => $targetType,
            'target_id' => $targetId,
            'action' => $action,
            'changes' => $changes ?: null,
            'source' => $source,
        ]);
    }
}

