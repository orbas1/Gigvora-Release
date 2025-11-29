<?php

namespace Jobs\Support\Search;

use Illuminate\Contracts\Pagination\Paginator;
use Jobs\Models\Job;

class JobSearchService
{
    public function search(array $filters = []): Paginator
    {
        $query = Job::with('company')->published();

        if (!empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }

        if (!empty($filters['employment_type'])) {
            $query->where('employment_type', $filters['employment_type']);
        }

        if (!empty($filters['workplace_type'])) {
            $query->where('workplace_type', $filters['workplace_type']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }
}
