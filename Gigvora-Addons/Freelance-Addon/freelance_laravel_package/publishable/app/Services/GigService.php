<?php

namespace App\Services;

use App\Models\Gig\Gig;
use App\Models\Gig\GigPlan;
use App\Models\FavouriteItem;
use App\Models\Profile;
use Clue\Redis\Protocol\Model\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GigService
{

    public function getGigs(array $filters, string $sort_by = 'date_desc')
    {
        $gigs = Gig::select('id', 'author_id', 'title', 'slug', 'country', 'address', 'attachments', 'is_featured', 'status')
            ->with([
                'gigAuthor:id,user_id,first_name,last_name,slug,image',
                'gigAuthor.user.userAccountSetting:id,user_id,verification',
            ])->whereHas('gigAuthor.user')->withAvg('ratings', 'rating')
            ->withMin('gig_plans as minimum_price', 'price')
            ->withCount(['ratings', 'gig_visits'])->where('status', 'publish');


        if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
            $gigs = $gigs->whereHas('gig_plans', function ($query) use ($filters) {
                $query->orderBy('price', 'asc');
                $query->whereBetween('price', [$filters['min_price'], $filters['max_price']]);
            });
        }

        if (!empty($filters['keyword'])) {
            $gigs = $gigs->whereFullText('title', $filters['keyword']);
        }

        if (!empty($filters['selected_location'])) {
            $gigs = $gigs->whereFullText('country', $filters['selected_location']);
        }


        if (!empty($filters['selected_category'])) {
            $gigs = $gigs->whereHas('categories', function ($query) use ($filters) {
                $query->where('category_id', $filters['selected_category']);
            });
        }

        if ($sort_by == 'date_desc') {
            $gigs = $gigs->orderBy('created_at', 'desc');
        } elseif ($sort_by == 'visits_desc') {
            $gigs = $gigs->orderByDesc("gig_visits_count");
        } elseif ($sort_by == 'order_desc') {
            $gigs = $gigs->withCount(['gig_orders' => function ($query) {
                $query->where('status', 'completed');
            }])->orderByDesc("gig_orders_count");
        }

        if (in_array($sort_by, ['price_desc', 'price_asc'])) {
            $sorting = $sort_by == 'price_desc' ? 'desc' : 'asc';
            $gigs = $gigs->orderBy('minimum_price', $sorting);
        }

        $user       = getUserRole();
        $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;

        $gigs->addSelect(DB::raw('(SELECT count(*) FROM favourite_items WHERE user_id = ' . $profile_id . ' AND corresponding_id = gigs.id AND type = "gig") AS is_favourite'));

        return $gigs->paginate($filters['per_page'] ?? 0);
    }

    public function getDetail(int $id)
    {
        $user = getUserRole();
        $profile_id = !empty($user['profileId']) ? $user['profileId'] : 0;

        $gig = Gig::select('id','title','author_id','description','attachments','status', 'is_featured', 'address')
        ->with([
            'ratings','ratings.gig_orders:id,author_id,gig_id',
            'ratings.gig_orders.orderAuthor:id,image',
            'faqs:id,gig_id,question,answer', 
            'addons:id,title,price,description',
            'gig_plans'
        ])
        ->withWhereHas('gigAuthor' , function($query){
            $query->select('id','user_id','first_name','last_name','image');
            $query->with(['user:id', 'user.userAccountSetting:id,user_id,verification']);
        })->withCount(['gig_visits','gig_orders' => function($query){
            $query->where('status', 'completed');
        }])->withAvg('ratings','rating');
        
        $gig = $gig->addSelect(DB::raw('(SELECT count(*) FROM favourite_items WHERE user_id = ' . $profile_id . ' AND corresponding_id = gigs.id AND type = "gig") AS is_favourite'));

        $gig = $gig->find($id);

        if(!empty($gig->id)){
            AddVisitCount( $gig->id, 'gig');
        }
        return $gig;
    }
}
