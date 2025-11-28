<?php
namespace App\Services;

use App\Models\Profile;
use App\Models\Role;
use App\Models\Seller\SellerPortfolio;
use Illuminate\Support\Facades\DB;

class SellerService {
    public function getSellers(array $filters, string $sort_by ){
       
        $profile  = Profile::where('role_id', Role::select('id')->where('name','seller')->first()->id)
        ->join('user_account_settings', 'user_account_settings.user_id', '=', 'profiles.user_id')
        ->select('profiles.id','profiles.user_id', 'slug', 'first_name', 'last_name', 'image', 'tagline','address','description', 'user_account_settings.hourly_rate', 'user_account_settings.verification')
        ->where( 'user_account_settings.verification', 'approved')->withCount('profile_visits')
        ->whereHas('user', fn($query) => $query->whereNotNull( 'email_verified_at'))
        ->withAvg('ratings','rating')->withCount('ratings')
        ->with('skills:id,name');

        if(!empty($filters['seller_min_hr_rate']) && !empty($filters['seller_max_hr_rate'])){
            $profile = $profile->whereBetween('user_account_settings.hourly_rate', [ $filters['seller_min_hr_rate'], $filters['seller_max_hr_rate'] ] );
        }

        if( !empty($filters['english_level']) ){
            $profile = $profile->where('english_level', $filters['english_level']);
        }

        if( !empty($filters['seller_type']) ){
            $profile = $profile->where('seller_type', $filters['seller_type']);
        }

        if( !empty($filters['selected_location']) ){
            $profile = $profile->where('country', $filters['selected_location']);
        }

        if( !empty($filters['profile_id']) ){
            $profile =  $profile->where('profiles.id', '!=', $filters['profile_id']);
        }

        if( $sort_by == 'date_desc' ){
            $profile = $profile->orderBy('profiles.created_at', 'desc');
        } elseif( $sort_by == 'price_asc' ){
            $profile->orderBy('user_account_settings.hourly_rate', 'asc');
        } elseif( $sort_by == 'price_desc' ){
            $profile->orderBy('user_account_settings.hourly_rate', 'desc');
        } elseif( $sort_by == 'visits_desc' ){
            $profile = $profile->orderByDesc("profile_visits_count");
        } elseif ($sort_by == 'review_rating') {
            $profile = $profile->orderByDesc("ratings_count");
        }

        if (!empty($filters['selected_skills'])) {
            $selected_skills = $filters['selected_skills'];

            if (!is_array($selected_skills)) {
                $selected_skills = explode(',', $selected_skills);
            }
            $profile = $profile->whereHas('skills', function($query) use ($selected_skills) {
                if (!empty($selected_skills)) {
                    $query->whereIn('skill_id', $selected_skills);
                }
            });
        }

        if( !empty($filters['keyword']) ){
            $profile->where(function($query) use ($filters) {
                $query->whereFullText('first_name',   $filters['keyword']);
                $query->orWhereFullText('last_name',  $filters['keyword']);
                $query->orWhereFullText('tagline',    $filters['keyword']);
                $query->orWhereFullText('description', $filters['keyword']);
            });
        }

        if( !empty($filters['languages']) ){
            $languages = is_array($filters['languages']) ? $filters['languages'] : explode(',', $filters['languages']);
            $profile = $profile->with('languages:id')->whereHas(
                'languages', function($query) use ($languages) {
                    $query->whereIn('language_id', $languages);
                }
            );
        }

        $user = getUserRole();

        $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;

        $profile->addSelect(DB::raw('(SELECT count(*) FROM favourite_items WHERE user_id = '. $profile_id . ' AND corresponding_id = profiles.id AND type = "profile") AS is_favourite'));

        return $profile->paginate($filters['per_page'] ?? 0);
    }


    public function sellerDetail($id)
    {
        $relations = [
            'skills:id,name',
            'languages:id,name',
            'user:id',
            'user.userAccountSetting:id,user_id,hourly_rate,verification',
            'education',
            'portfolio'
        ];
        $seller_role_id = getRoleByName('seller');
        $profile = Profile::select(
            'id',
            'user_id',
            'first_name',
            'last_name',
            'description',
            'image',
            'address',
            'tagline',
            'english_level',
            'seller_type'
        )->with($relations)
        ->with('gigs', function($query){
            $query->withMin('gig_plans as minimum_price', 'price');
        })
        ->withCount('profile_visits', 'ratings')
        ->withAvg('ratings', 'rating')
        ->where('role_id', $seller_role_id);




        $user = getUserRole();
        $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;
        $profile->addSelect(DB::raw('(SELECT count(*) FROM favourite_items WHERE user_id = '. $profile_id . ' AND corresponding_id = profiles.id AND type = "profile") AS is_favourite'));
        $profile = $profile->find($id);

        if ($profile) {
            AddVisitCount($profile->id, 'profile');
        }

        return $profile;
    }

    public function getPortfolios()
    {
        $user = getUserRole();
        $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;
        return SellerPortfolio::where('profile_id', $profile_id)->orderBy('id','DESC')->get();
    }

    public function updatePortfolio($id, $params)
    {
        $user = getUserRole();
        $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;
        $portfolio = SellerPortfolio::find($id);
        $type = '';
        if(!empty($portfolio)){

            $data['profile_id'] = $profile_id;
            $data['title'] = $params['title'];
            $data['url'] = $params['url'];
            $data['description'] = $params['description'];
            $attachments = [];

            $image_dimensions = getImageDimensions('portfolios');
            if(!empty($params['files'])){
                foreach($params['files'] as $key => $single){
                    $file = (object) $single;
                    if( method_exists($file,'getClientOriginalName') ) {
                        $file_path      = $file->store('public/portfolios');
                        $file_path      = str_replace('public/', '', $file_path);
                        $file_name      = $file->getClientOriginalName();
                        $file_key       = pathinfo($file->hashName(), PATHINFO_FILENAME);
                        $mime_type      = $file->getMimeType();
                        $sizes          = generateThumbnails('portfolios', $file, $image_dimensions);
                        $attachments['files'][$file_key]  = (object) array(
                            'file_name'  => $file_name,
                            'file_path'  => $file_path,
                            'mime_type'  => $mime_type,
                            'sizes'      => $sizes,
                        );
                    }

                }

                $data['attachments'] = !empty($attachments) ? serialize($attachments) : null;
            }

            $isUpdate = $portfolio->update($data);

            if($isUpdate){
                $type = 'success';
            } else {
                $type = 'error';
            }
        } else {
            $type = 'not_found';
        }
        return $type;
    }

    public function createPortfolio($params)
    {

        $user = getUserRole();
        $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;
        $portfolio_id = !empty($portfolio_id) ? $portfolio_id : 0;

        $data['profile_id']     = $profile_id;
        $data['title']          = $params['title'];
        $data['url']            = $params['url'];
        $data['description']    = $params['description'];
        $attachments            = [];

        $image_dimensions = getImageDimensions('portfolios');
        if(!empty($params['files'])){
            foreach($params['files'] as $key => $single){
                $file = (object) $single;
                if( method_exists($file,'getClientOriginalName') ) {
                    $file_path      = $file->store('public/portfolios');
                    $file_path      = str_replace('public/', '', $file_path);
                    $file_name      = $file->getClientOriginalName();
                    $file_key       = pathinfo($file->hashName(), PATHINFO_FILENAME);
                    $mime_type      = $file->getMimeType();
                    $sizes          = generateThumbnails('portfolios', $file, $image_dimensions);
                    $attachments['files'][$file_key]  = (object) array(
                        'file_name'  => $file_name,
                        'file_path'  => $file_path,
                        'mime_type'  => $mime_type,
                        'sizes'      => $sizes,
                    );
                }
            }

            $data['attachments'] = !empty($attachments) ? serialize($attachments) : null;
        }
        return SellerPortfolio::create($data);
    }

    public function deletePortfolio($id)
    {
        $user = getUserRole();
        $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;
        return SellerPortfolio::where(['id' => $id, 'profile_id' => $profile_id])->delete();

    }
}
