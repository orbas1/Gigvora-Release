<?php

namespace ProNetwork\Services;

use ProNetwork\Models\AccountType;
use ProNetwork\Models\UserAccountType;
use ProNetwork\Models\UserFeatureFlag;
use ProNetwork\Support\Enums\AccountTypeEnum;

class AccountTypeService
{
    public function assign(int $userId, AccountTypeEnum $type): UserAccountType
    {
        $accountType = AccountType::firstOrCreate([
            'slug' => $type->value,
        ], [
            'name' => ucfirst($type->value),
        ]);

        return UserAccountType::updateOrCreate([
            'user_id' => $userId,
            'account_type_id' => $accountType->id,
        ], []);
    }

    public function featureEnabled(int $userId, string $feature): bool
    {
        return UserFeatureFlag::where('user_id', $userId)->where('feature', $feature)->value('enabled') ?? false;
    }

    public function setFeature(int $userId, string $feature, bool $enabled): UserFeatureFlag
    {
        return UserFeatureFlag::updateOrCreate(
            ['user_id' => $userId, 'feature' => $feature],
            ['enabled' => $enabled]
        );
    }
}
