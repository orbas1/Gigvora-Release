<?php

namespace App\Services;

// use Illuminate\Database\Eloquent\Collection;

use App\Models\FavouriteItem;
use App\Models\Project;
use App\Models\Taxonomies\ProjectCategory;
use Illuminate\Support\Facades\DB;

class ProjectService {

    public function getProjects(array $filters, string $order_by = 'date_desc' ) {

        $projects = Project::select('id', 'author_id', 'project_title', 'slug', 'updated_at', 'project_type', 'project_description', 'project_min_price',
                                    'project_location', 'project_country', 'project_expert_level', 'project_duration', 'project_max_price', 'address',
                                    'project_hiring_seller', 'is_featured', 'status')
                                    ->with([
                                        'expertiseLevel:id,name',
                                        'projectLocation:id,name',
                                        'projectAuthor:id,first_name,last_name,image',
                                        'skills:id,name',
                                        'projectDuration:id,name'
                                    ])->has('projectAuthor')->where('status', 'publish');

                                    if (!empty($filters['selected_skills'])) {
                                        $selectedSkills = is_array($filters['selected_skills']) ? $filters['selected_skills'] : explode(',', $filters['selected_skills']);
                                        $projects = $projects->whereHas('skills', function($query) use ($selectedSkills) {
                                            $query->whereIn('skill_id', $selectedSkills);
                                        });
                                    }

                                    if( !empty($filters['keyword']) ){
                                        $projects = $projects->where(function($query) use($filters) {
                                            $query->whereFullText('project_title', $filters['keyword']);
                                        });

                                    }

                                    if (!empty($filters['selected_languages'])) {
                                        $selectedLanguages = is_array($filters['selected_languages']) ? $filters['selected_languages'] : explode(',', $filters['selected_languages']);
                                        $projects = $projects->with('languages:id')->whereHas(
                                            'languages', function($query) use ($selectedLanguages) {
                                                $query->whereIn('language_id', $selectedLanguages);
                                            }
                                        );
                                    }

                                    if( !empty($filters['selected_expertise_levels']) ){
                                        $selectedExpertiseLevels = is_array($filters['selected_expertise_levels']) ? $filters['selected_expertise_levels'] : explode(',', $filters['selected_expertise_levels']);
                                        $projects = $projects->whereIn('project_expert_level', $selectedExpertiseLevels);
                                    }

                                    if( !empty($filters['author_id']) ){
                                        $projects = $projects->whereAuthorId($filters['author_id']);
                                    }

                                    if( !empty($filters['project_type']) && $filters['project_type'] != 'all'){
                                        $projects = $projects->where('project_type', $filters['project_type']);
                                    }

                                    if( !empty($filters['selected_category']) ){
                                        $projects = $projects->where('project_category', $filters['selected_category']);
                                    }elseif( !empty($filters['category_name'])){
                                        $category = ProjectCategory::select('id')->where('slug','like','%'. $filters['category_name'].'%')->first();
                                        if( !empty($category) ){
                                            $projects = $projects->where('project_category', $category->id);
                                            $filters['selected_category'] = $category->id;
                                        }
                                    }

                                    if( !empty($filters['project_min_price']) || !empty($filters['project_max_price'])) {
                                        if(!empty($filters['project_min_price'])){
                                            $projects   =  $projects->where('project_min_price', '>=', $filters['project_min_price']);
                                        }
                                        if(!empty($filters['project_max_price'])){
                                            $projects   =  $projects->where('project_max_price', '<=', $filters['project_max_price']);
                                        }
                                    }

                                    if (!empty($filters['selected_location'])) {
                                         // $selected_location_name = DB::table('countries')->where('id', $filters['selected_location'])->value('name');
                                        $projects = $projects->where('project_country', $filters['selected_location']);
                                    }

                                    $projects = $projects->withCount('projectVisits');

                                    if( $order_by == 'date_desc' ){
                                        $projects = $projects->orderBy('updated_at', 'desc');
                                    }elseif( $order_by == 'price_desc' ){
                                        $projects = $projects->orderBy('project_min_price', 'desc');
                                    }elseif( $order_by == 'price_asc' ){
                                        $projects = $projects->orderBy('project_min_price', 'asc');
                                    }elseif( $order_by == 'visits_desc' ){
                                        $projects = $projects->orderByDesc("project_visits_count");
                                    }

                                    // if(!empty($filters['selected_category'])){
                                    //     $this->emit('updateCategroyId', $filters['selected_category']);
                                    // }
                                    // if(!empty($filters['keyword'])){
                                    //     $this->dispatchBrowserEvent('totalFoundResult', ['total_count' => $projects->count(), 'keyword' => clean( $filters['keyword'] ) ] );
                                    // }
                                    $user = getUserRole();
                                    $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;
                                    $projects->addSelect(DB::raw('(SELECT count(*) FROM favourite_items WHERE user_id = '. $profile_id . ' AND corresponding_id = projects.id AND type = "project") AS is_favourite'));

                                   return $projects->orderByDesc('is_featured')->paginate($filters['per_page'] ?? 0);
    }

    public function favouriteProjects($profile_id){
        return FavouriteItem::select('corresponding_id')->where(['user_id' => $profile_id, 'type' => 'project'])->pluck('corresponding_id')->toArray();

    }

    public function getProject($slug){
        $project = Project::with([
            'projectDuration:id,name',
            'projectLocation:id,name',
            'expertiseLevel:id,name',
            'category:id,name',
            'skills:id,name',
            'languages:id,name',
            'projectAuthor:id,user_id,first_name,last_name,image,description,created_at',
            'projectAuthor.user:id',
            'projectAuthor.user.userAccountSetting:id,user_id,verification'
        ]);
        $user = getUserRole();
        $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;

        $project = $project->select('projects.*')
                    ->addSelect(DB::raw('(SELECT count(*) FROM favourite_items WHERE user_id = '. $profile_id . ' AND corresponding_id = projects.id AND type = "project") AS is_favourite'))
                    ->addSelect(DB::raw('(SELECT count(*) FROM proposals WHERE author_id = '. $profile_id . ' AND project_id = projects.id) AS is_applied'));

        return $project->where('slug', $slug)->first();
    }

    public function getPopularProjects($per_page)
    {
        $projects = Project::select('id', 'author_id', 'project_title', 'slug', 'updated_at', 'project_type', 'project_description', 'project_min_price',
        'project_location', 'project_country', 'project_expert_level', 'project_duration', 'project_max_price', 'address',
        'project_hiring_seller', 'is_featured', 'status')
        ->with([
            'expertiseLevel:id,name',
            'projectLocation:id,name',
            'projectAuthor:id,first_name,last_name',
            'skills:id,name',
            'projectDuration:id,name'
        ])->has('projectAuthor')
        ->where('status', 'publish')->withCount('projectVisits')->orderBy('updated_at', 'desc');
       return $projects->orderByDesc('is_featured')->paginate($per_page);
}
}

