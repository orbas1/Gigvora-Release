<?php

namespace App\Support\Authorization;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PermissionMatrix
{
    public function __construct(protected array $config = [])
    {
        $this->config = $config ?: config('permission_matrix', []);
    }

    public function permissions(): array
    {
        return $this->config['permissions'] ?? [];
    }

    public function roles(): array
    {
        return $this->config['roles'] ?? [];
    }

    public function allowed(?Authenticatable $user, ?string $permission): bool
    {
        if (blank($permission)) {
            return true;
        }

        if (! $user) {
            return false;
        }

        $matrix = $this->permissions();
        if (! array_key_exists($permission, $matrix)) {
            return false;
        }

        $role = $this->resolveRole($user);
        $allowed = $matrix[$permission]['roles'] ?? [];

        if (in_array($role, $allowed, true)) {
            return true;
        }

        $adminRoles = $this->config['defaults']['global_admin_roles'] ?? [];

        return in_array($role, $adminRoles, true);
    }

    public function eventName(string $domain, string $key): ?string
    {
        $events = $this->config['analytics']['events'][$domain] ?? [];

        return $events[$key] ?? null;
    }

    public function analyticsProperties(?Authenticatable $user, array $properties = []): array
    {
        $actorKey = $this->config['defaults']['actor_key'] ?? 'user_id';
        $actorValue = $user?->getAuthIdentifier();

        return Arr::whereNotNull([$actorKey => $actorValue]) + $properties;
    }

    protected function resolveRole(Authenticatable $user): string
    {
        $role = $user->user_role ?? null;

        if (! $role && function_exists('getUserRole')) {
            $role = data_get(getUserRole(), 'roleName');
        }

        return Str::lower($role ?: $this->config['defaults']['fallback_role'] ?? 'member');
    }
}
