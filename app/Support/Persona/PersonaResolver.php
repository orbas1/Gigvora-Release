<?php

namespace App\Support\Persona;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class PersonaResolver
{
    public function resolve(User $user): string
    {
        if (! DB::getSchemaBuilder()->hasTable('pro_network_user_account_types')) {
            return 'member';
        }

        $types = DB::table('pro_network_user_account_types')
            ->join('pro_network_account_types', 'pro_network_user_account_types.account_type_id', '=', 'pro_network_account_types.id')
            ->where('pro_network_user_account_types.user_id', $user->id)
            ->pluck('pro_network_account_types.slug')
            ->filter()
            ->map(fn ($slug) => strtolower($slug))
            ->unique();

        $hasProfessional = $types->contains('professional');
        $hasCreator = $types->contains('creator');

        if ($hasProfessional && $hasCreator) {
            return 'hybrid';
        }

        if ($hasProfessional) {
            return 'professional';
        }

        return 'member';
    }
}

