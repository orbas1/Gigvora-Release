<?php

namespace App\Http\Livewire\Seller;

use Carbon\Carbon;
use App\Models\Gig\Gig;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SellerGigs extends Component
{
    
    public $user_profile_id = '';
    public $profile_id      = '';
    public $user_role       = '';
    public $address_format  = '';
    public $currency_symbol = '';
    public $seller_name     = '';
    public $verify_status   = '';
    public $page_loaded     = false; 
   
    public function mount($user_profile_id, $address_format, $currency_symbol, $seller_name, $verify_status, $user_role){

        $this->user_role        = $user_role;
        $this->address_format   = $address_format;
        $this->currency_symbol  = $currency_symbol; 
        $this->user_profile_id  = $user_profile_id; 
        $this->seller_name      = $seller_name; 
        $this->verify_status    = $verify_status; 

        $user                   = getUserRole();
        $this->profile_id       = !empty($user['profileId']) ? $user['profileId'] : '';
    }

    public function render(){
        
        $gigs = [];
        if( $this->page_loaded ){
            $gigs = Gig::select('id','author_id','title','address', 'slug', 'attachments', 'is_featured')
                ->where(['author_id'=> $this->user_profile_id, 'status' => 'publish'])->with([
                'gig_plans:id,gig_plans.gig_id,price',
            ])->withCount('gig_visits')->withAvg('ratings','rating')->withCount('ratings')->orderBy('id', 'desc');
           
            $this->dispatchBrowserEvent('initializeSlider',['gig_count' => $gigs->count()]);
            if( !empty($this->profile_id) ){
                $gigs->addSelect(DB::raw('(SELECT count(*) FROM favourite_items WHERE user_id = ' . $this->profile_id . ' AND corresponding_id = gigs.id AND type = "gig") AS is_favourite'));

            }

            $gigs = $gigs->get();
        }

        return view('livewire.seller.seller-gigs', compact('gigs'));
    }

    public function loadGigs(){
        $this->page_loaded = true;
    }

    public function favouriteGig($gig_id){

        $response = isDemoSite();
        if( $response ){
            $this->dispatchBrowserEvent('showAlertMessage', [
                'type'      => 'error',
                'title'     => __('general.demosite_res_title'),
                'message'   => __('general.demosite_res_txt')
            ]);
            return;
        }

        if($this->user_role == 'buyer'){
            $savItem = favouriteItem($this->profile_id, $gig_id, 'gig');
        } else {
            $eventData              = [];
            $eventData['title']     = __('general.error_title');
            $eventData['message']   = __('general.login_error');
            $eventData['type']      = 'error';
            $this->dispatchBrowserEvent('showAlertMessage', $eventData);
        }
    }
}
