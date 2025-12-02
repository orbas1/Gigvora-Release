<?php

namespace App\Support\Navigation;

use App\Support\Authorization\PermissionMatrix;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Route;

class NavigationBuilder
{
    protected ?Authenticatable $user;

    public function __construct(protected PermissionMatrix $permissions)
    {
    }

    public function build(?Authenticatable $user): array
    {
        $this->user = $user;
        $config = config('navigation', []);

        return [
            'primary' => $this->filterItems($config['primary'] ?? []),
            'groups' => $this->filterItems($config['groups'] ?? [], true),
            'secondary' => $this->filterItems($config['secondary'] ?? []),
            'admin' => $this->filterItems($config['admin'] ?? []),
            'profile_tabs' => $this->filterItems($config['profile_tabs'] ?? []),
            'settings' => $this->filterItems($config['settings'] ?? []),
            'mobile' => $this->buildMobile($config['mobile'] ?? []),
        ];
    }

    protected function filterItems(array $items, bool $processChildren = false): array
    {
        return collect($items)
            ->map(function (array $item) use ($processChildren) {
                if ($processChildren) {
                    $item['children'] = $this->filterItems($item['children'] ?? []);
                }

                return $item;
            })
            ->filter(function (array $item) use ($processChildren) {
                if (! $this->featureEnabled($item['feature'] ?? null)) {
                    return false;
                }

                if (! $this->permissionAllowed($item['permission'] ?? null)) {
                    return false;
                }

                $route = $item['route'] ?? null;
                if ($route && ! Route::has($route)) {
                    return false;
                }

                if ($processChildren && empty($item['children'])) {
                    return false;
                }

                return true;
            })
            ->values()
            ->all();
    }

    protected function featureEnabled(?string $feature): bool
    {
        if (blank($feature)) {
            return true;
        }

        $value = config($feature);

        return is_null($value) ? true : (bool) $value;
    }

    protected function permissionAllowed(?string $permission): bool
    {
        if (blank($permission)) {
            return true;
        }

        if (! $this->user) {
            return false;
        }

        return $this->permissions->allowed($this->user, $permission);
    }

    protected function buildMobile(array $config): array
    {
        return [
            'tabs' => $this->filterItems($config['tabs'] ?? []),
            'drawer' => $this->filterItems($config['drawer'] ?? [], true),
        ];
    }
}

