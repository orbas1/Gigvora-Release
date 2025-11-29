<?php

namespace App\Services;

use App\Models\FavouriteItem;
use App\Models\Gig\GigPlan;

class GeneralService
{
    public function getSavedItem($type = 'profile', $search= '', $per_page = 20 )
    {
        $user = getUserRole();
        $profile_id = !empty( $user['profileId'] ) ? $user['profileId'] : '';
        $items = FavouriteItem::where(['user_id' => $profile_id, 'type' => $type]);

        if( $type == 'project' ){
            $items = $items->with('projects', function($query) use ($profile_id) {
                $query->select(
                    'id','author_id','project_title', 'slug', 'updated_at', 'project_type', 'project_country',
                    'project_min_price', 'project_location', 'project_expert_level', 'project_duration',
                    'project_max_price', 'address', 'project_hiring_seller', 'is_featured', 'status',
                )->with([
                    'projectAuthor:id,first_name,last_name,image',
                    'projectLocation:id,name', 'expertiseLevel:id,name',
                    'proposals' => function ($query) use ($profile_id) {
                        $query->where('author_id', $profile_id);
                        $query->select('id','author_id','project_id','status','decline_reason');
                    }
                ]);
            });
            if(!empty($search)){
                $items = $items->whereHas('projects', function($query) use ($search){
                    $query->whereFullText('project_title', $$search);
                });
            }

        } elseif( $type == 'gig' ){
           
            $items = $items->with('gigs', function($query){
                $query->select('id','author_id','title','slug','country','address','attachments','is_featured','status');
                $query->with([
                    'gigAuthor:id,user_id,first_name,last_name,slug,image',
                    'gigAuthor.user.userAccountSetting:id,user_id,verification',
                ]);
                $query->withCount('gig_visits')->withAvg('ratings','rating')->withCount('ratings');
                $minumumValue = GigPlan::select('price')
                ->whereColumn('gig_plans.gig_id', 'gigs.id')
                ->orderBy('price', 'asc')
                ->limit(1);
                $query->addSelect(['minimum_price' => $minumumValue]);
            })->whereHas('gigs', function($query){
                $query->where('status', 'publish');
                if(!empty($search)){
                    $query->whereFullText('title', $search);
                }
            });

        } elseif( $type == 'profile'){
            $items = $items->with('sellers', function($query){
                $query->select('id','user_id','first_name', 'last_name','slug', 'image', 'tagline', 'description', 'address');
                $query->with(['user:id','user.userAccountSetting:id,user_id,hourly_rate']);
                $query->withCount('profile_visits');
                $query->withAvg('ratings','rating')->withCount('ratings');
            })->has('sellers');
            if(!empty($search)){
                $items = $items->whereHas('sellers', function($query) use ($search) {
                    $query->whereFullText('first_name', $search)->orWhereFullText('last_name', $search);
                });
            }
        }

        return $items->orderBy('id', 'desc')->paginate($per_page);

    }
}