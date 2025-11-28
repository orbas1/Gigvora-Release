<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\ProjectCategoryResouce;
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\ExpertLevel\ExpertLevelResouce;
use App\Http\Resources\GigCategory\GigCategoryResource;
use App\Http\Resources\GigDeliveryTime\GigDeliveryTimeCollection;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\ProjectDuration\ProjectDurationCollection;
use App\Http\Resources\ProjectLocation\ProjectLocationResource;
use App\Http\Resources\Skill\SkillResource;
use App\Http\Resources\Tag\TagCollection;
use App\Http\Resources\TaxonomyResource;
use App\Services\TaxonomyService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class TaxonomyController extends Controller
{
    use ApiResponser;




    public function getTaxonomies(Request $request)
    {
        $record = (new TaxonomyService)->getTaxonomy($request);
        if(!empty($record['type']) && $record['type'] == 'success'){
            return $this->success(TaxonomyResource::collection($record['data']), __('taxonomy.tax_list'));
        } else {
            return $this->error($record['message']);
        }
    }

    public function getCountries(Request $request)
    {
        $record = (new TaxonomyService)->getAllCountries($request);
        if(!empty($record['type']) && $record['type'] == 'success'){
            return $this->success(TaxonomyResource::collection($record['data']), __('taxonomy.tax_list'));
        } else {
            return $this->error($record['message']);
        }
    }

    public function getAllCountryState(Request $request)
    {
        $record = (new TaxonomyService)->getAllCountryState($request);
        if(!empty($record['type']) && $record['type'] == 'success'){
            return $this->success(TaxonomyResource::collection($record['data']), __('taxonomy.tax_list'));
        } else {
            return $this->error($record['message']);
        }
    }
}
