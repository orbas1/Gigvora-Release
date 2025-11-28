<?php

namespace App\Services;

use App\Models\Dispute;

class DisputeService
{

    public function getDisputes(string $search = '', string $status = '', int $per_page = 20)
    {
        $user = getUserRole();
        $profile_id = $user['profileId']; 
        $all_statuses = ['publish','declined','disputed','resolved','refunded'];
        $statuses = in_array($status, $all_statuses) ? [$status] : $all_statuses;
        $fullTextSearch = function($query, $search) {
            $query->whereFullText('first_name', $search)
            ->orWhereFullText('last_name', $search)
            ->orWhereFullText('tagline', $search)
            ->orWhereFullText('description', $search);
        };
        
        return Dispute::where(function($query) use ($profile_id, $search, $fullTextSearch) {
            $query->where('created_by', $profile_id)->orWhere('created_to', $profile_id);
            if (!empty($search)) {
                $query->where(function($query) use ($profile_id, $search, $fullTextSearch) {
                    $query->whereHas('disputeCreator', function($subQuery) use ($profile_id, $search, $fullTextSearch) {
                        $subQuery->where('created_to', $profile_id)
                        ->where(function($query) use ($search, $fullTextSearch) {
                            $fullTextSearch($query, $search);
                        });
                    })->orWhereHas('disputeReceiver', function($subQuery) use ($profile_id, $search, $fullTextSearch) {
                        $subQuery->where('created_by', $profile_id)
                        ->where(function($query) use ($search, $fullTextSearch) {
                            $fullTextSearch($query, $search);
                        });
                    });
                });
            }
        })->has('disputeCreator')->has('disputeReceiver')
        ->with([
            'disputeCreator:id,first_name,last_name,role_id',
            'disputeReceiver:id,first_name,last_name,role_id'
        ])->whereIn('status', $statuses)
        ->orderBy('id', 'desc')->paginate($per_page);
    }
}
