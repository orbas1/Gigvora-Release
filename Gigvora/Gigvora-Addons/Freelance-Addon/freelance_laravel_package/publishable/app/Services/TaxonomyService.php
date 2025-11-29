<?php

namespace App\Services;

use App\Models\Country;
use App\Models\CountryState;
use App\Models\Gig\GigDeliveryTime;
use App\Models\Taxonomies\ExpertLevel;
use App\Models\Taxonomies\GigCategory;
use App\Models\Taxonomies\Language;
use App\Models\Taxonomies\ProjectCategory;
use App\Models\Taxonomies\ProjectDuration;
use App\Models\Taxonomies\ProjectLocation;
use App\Models\Taxonomies\Skill;
use App\Models\Taxonomies\Tag;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TaxonomyService {

    public function getAllProjectCategories() {
        $categories_tree = ProjectCategory::where('status', 'active')->tree()->get();
        return hierarchyTree($categories_tree);
    }

    public function getTaxonomy($request) {
        $type = $request->type;
        $search = $request->search;
        $per_page = $request->per_page;

        $models = [
            'skills'                => 'App\Models\Taxonomies\Skill',
            'languages'             => 'App\Models\Taxonomies\Language',
            'countries'             => 'App\Models\Country',
            'expert_levels'         => 'App\Models\Taxonomies\ExpertLevel',
            'gig_categories'        => 'App\Models\Taxonomies\GigCategory',
            'tags'                  => 'App\Models\Taxonomies\Tag',
            'gig_delivery_time'     => 'App\Models\Gig\GigDeliveryTime',
            'project_durations'     => 'App\Models\Taxonomies\ProjectDuration',
            'project_locations'     => 'App\Models\Taxonomies\ProjectLocation',
            'project_categories'    => 'App\Models\Taxonomies\ProjectCategory',
        ];

        if($type == 'business_types'){
            $business_types = setting('_seller.seller_business_types') ?? [];
            $collection = collect($business_types)->map(function ($item) {
                return (object) [
                    'id' => $item['business_types'] ?? '',
                    'name' => $item['business_types'] ?? '',
                ];
            });

            return ['type' => 'success', 'data' => $collection];
        }

        if($type == 'english_level'){
            $business_types = setting('_seller.seller_business_types') ?? [];
            $collection = collect([
                'basic'             => __('profile_settings.basic_level'),
                'conversational'    => __('profile_settings.conversational_level'),
                'fluent'            => __('profile_settings.fluent_level'),
                'native'            => __('profile_settings.native_level'),
                'professional'      => __('profile_settings.professional_level'),
            ])->map(function ($value, $key ) {
                return (object) [
                    'id' => $key ?? '',
                    'name' => $value ?? '',
                ];
            });
            return ['type' => 'success', 'data' => $collection];
        }

        if (!array_key_exists($type, $models)) {
            return [ 'type' => 'error', 'message' => __('taxonomy.invalid_tax_type')];
        }

        $modelClass = $models[$type];

        $data = $modelClass::select('id', 'name')->when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        if(!empty($per_page)){
            $data = $data->take($per_page);
        }

        $data = $data->get();

        return [ 'type' => 'success', 'data' => $data];
    }

    public function getAllExpertLevels() {
        return ExpertLevel::select('id', 'name')->get();
    }

    public function getAllLanguages() {
        return Language::select('id', 'name')->get();
    }

    public function getAllCountries($request) {
        $countries = Country::select('id', 'name');
        if(!empty($request->name)) {
            $countries = $countries ->where('name', 'like', '%' . $request->name . '%');
        }
        $countries  = $countries ->get();

        return ['type' => 'success', 'data' => $countries];
    }

    public function getAllCountryState($request)
    {

        if(!empty($request->country_id)){
            $states = CountryState::select('id','name')->where('country_id', $request->country_id);

            if(!empty($request->name)) {
                $states = $states ->where('name', 'like', '%' . $request->name . '%');
            }
            $states  = $states->get();

            return ['type' => 'success', 'data' => $states];
        } else {
            return ['type' => 'error', 'data' => []];
        }

    }

    public function getAllGigCategories() {
        return GigCategory::select('id', 'name')->get();
    }

    public function getAllTags() {
        return Tag::select('id', 'name')->get();
    }

    public function getGigDeliveryTimes() {
        return GigDeliveryTime::select('id', 'name')->get();
    }

    public function getAllProjectDurations() {
        return ProjectDuration::select('id', 'name')->get();
    }

    public function getAllProjectLocations() {
        return ProjectLocation::select('id', 'name')->get();
    }

    public function getProjectCategories(array $filters, string $order_by = 'desc') {

        return ProjectCategory::when(!empty($filters['search']), function ($query) use ($filters) {
            $query->where(function ($where) use ($filters) {
                $where->whereFullText('name', $filters['search'])
                ->orWhereFullText('description', $filters['search']);
            });
        })->orderBy('id', $order_by)->paginate($filters['per_page']);
    }

// For future use
    // public function getGigCategories(array $filters, string $order_by = 'desc') {

    //     return GigCategory::when(!empty($filters['search']), function ($query) use ($filters) {
    //         $query->where(function ($where) use ($filters) {
    //             $where->whereFullText('name', $filters['search'])
    //             ->orWhereFullText('description', $filters['search']);
    //         });
    //     })->orderBy('id', $order_by)->paginate($filters['per_page']);
    // }

    // public function getTags(array $filters, string $order_by = 'desc'){

    //     return Tag::when(!empty($filters['search']), function ($query) use ($filters) {
    //         $query->where(function ($where) use ($filters) {
    //             $where->whereFullText('name', $filters['search']);
    //         });
    //     })->orderBy('id', $order_by)->paginate($filters['per_page']);
    // }

    // public function getGigDeliveryTime(array $search, string $order_by = 'desc'){

    //     return GigDeliveryTime::when(!empty($search), function ($query) use ($search) {
    //         $query->where(function ($where) use ($filters) {
    //             $where->where('name', 'like', '%' . request('search') . '%');
    //         });
    //     })->orderBy('id', $order_by)->paginate($filters['per_page']);
    // }

    public function getSkills(array $filters, string $order_by = 'desc'){

        return Skill::when(!empty($filters['search']), function ($query) use ($filters) {
            $query->where(function ($where) use ($filters) {
                $where->whereFullText('name', $filters['search'])
                ->orWhereFullText('description', $filters['search']);
            });
        })->orderBy('id', $order_by)->paginate($filters['per_page']);
    }

    public function getLanguages(array $filters, string $order_by = 'desc') {

        return Language::when(!empty($filters['search']), function ($query) use ($filters) {
            $query->where(function ($where) use ($filters) {
                $where->whereFullText('name', $filters['search'])
                ->orWhereFullText('description', $filters['search']);
            });
        })->orderBy('name', $order_by)->paginate($filters['per_page']);
    }

    // public function getProjectDuration(array $filters, string $order_by = 'desc'){

    //     return ProjectDuration::when(!empty($filters['search']), function ($query) use ($filters) {
    //         $query->where(function ($where) use ($filters) {
    //             $where->whereFullText('name', $filters['search']);
    //         });
    //     })->orderBy('id', $order_by)->paginate($filters['per_page']);
    // }

    public function getProjectLocation(array $filters, string $order_by = 'desc'){

        return ProjectLocation::when(!empty($filters['search']), function ($query) use ($filters) {
            $query->where(function ($where) use ($filters) {
                $where->whereFullText('name', $filters['search']);
            });
        })->orderBy('id', $order_by)->get();
    }

    public function getExpertLevel(array $filters, string $order_by = 'desc'){

        return ExpertLevel::when(!empty($filters['search']), function ($query) use ($filters) {
            $query->where(function ($where) use ($filters) {
                $where->whereFullText('name', $filters['search']);
            });
        })->orderBy('id', $order_by)->paginate($filters['per_page']);
    }
// end for future use
}

