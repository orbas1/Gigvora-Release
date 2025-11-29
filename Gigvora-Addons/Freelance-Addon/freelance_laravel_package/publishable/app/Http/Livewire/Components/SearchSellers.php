<?php

namespace App\Http\Livewire\Components;

use App\Models\Profile;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FavouriteItem;
use App\Services\SellerService;

class SearchSellers extends Component
{
    use WithPagination;
    protected $listeners = ['ApplySearchFilter' => 'searchFilter'];


    public $selected_skills    = [];
    public $keyword            = '';
    public $languages = '';

    public $seller_min_hr_rate = '';
    public $seller_max_hr_rate = '';
    public $order_by = 'date_desc';
    public $profile_id = '';
    public $per_page = '';

    public $currency_symbol = '';
    public $date_format = '';
    public $def_min_hr_rate = '';
    public $def_max_hr_rate = '';
    public $isloadedPage = false;
    public $roleName = '';
    public $address_format = '';
    public $seller_type = '';
    public $english_level = '';
    public $selected_location = '';

    public function mount( $per_page, $currency_symbol, $date_format, $seller_min_hr_rate, $seller_max_hr_rate, $keyword, $address_format ){
        $this->address_format   = $address_format;
        $this->per_page         = $per_page;
        $this->currency_symbol  = $currency_symbol;
        $this->date_format      = $date_format;
        $this->keyword          = $keyword;
        $this->seller_min_hr_rate = $this->def_min_hr_rate = $seller_min_hr_rate;
        $this->seller_max_hr_rate = $this->def_max_hr_rate = $seller_max_hr_rate;
        $user = getUserRole();
        $this->profile_id         = !empty($user['profileId']) ? $user['profileId'] : 0;
        $this->roleName         = !empty($user['roleName']) ? $user['roleName'] : 0;

    }

    public function loadSellers(){
        $this->isloadedPage = true;
    }

    public function render(){

        $sellers            = [];
        $favourite_sellers  = [];
        if( $this->isloadedPage ){

            $filters = [ 
                'selected_skills' => $this->selected_skills,
                'keyword' => $this->keyword,
                'languages' => $this->languages,
                'seller_min_hr_rate' => $this->seller_min_hr_rate,
                'seller_max_hr_rate' => $this->seller_max_hr_rate,
                'english_level' => $this->english_level,
                'seller_type' => $this->seller_type,
                'selected_location' => $this->selected_location,
                'profile_id' => $this->profile_id,
                'per_page' => $this->per_page,
            ];

            $sellers = (new SellerService)->getSellers($filters, $this->order_by);

            // if( $this->profile_id ){
            //     $favourite_sellers  = FavouriteItem::select('corresponding_id')->where('user_id', $this->profile_id)->pluck('corresponding_id')->toArray();
            // }

            $this->dispatchBrowserEvent('totalFoundResult', ['total_count' => $sellers->count(), 'keyword' => clean( $this->keyword ) ] );

        }
        
        return view('livewire.components.search-sellers', compact('sellers'));
    }

    public function searchFilter($data){

        $type = !empty($data['type']) ? $data['type'] : '';
        if(in_array($type, ['keyword', 'skills', 'seller_type', 'english_level', 'languages', 'pricerange', 'location'])){
            $this->resetPage();
        }

        switch($type){
            case 'keyword':
                $this->keyword                  = !empty($data['keyword']) ? $data['keyword'] : '';
            break;
            case 'skills':
                $this->selected_skills          = !empty($data['skills']) ? $data['skills']: [];
            break;
            case 'seller_type':
                $this->seller_type    = !empty($data['seller_types']) ? $data['seller_types']:[];
            break;
            case 'english_level':
                $this->english_level   = !empty($data['english_levels']) ? $data['english_levels']:[];
            break;
            case 'languages':
                $this->languages       = !empty($data['languages']) ? $data['languages'] : [];
            break;
            case 'pricerange':
                $this->seller_min_hr_rate       = !empty($data['min_price']) ? $data['min_price'] : '';
                $this->seller_max_hr_rate       = !empty($data['max_price']) ? $data['max_price'] : '';
            break;
            case 'location':
                $this->selected_location        = !empty($data['location']) ? $data['location'] : '';
            break;
            case 'orderby':
                $this->order_by                 = !empty($data['orderby']) ? $data['orderby'] : '';
            break;
            case 'clear_filter':
                $this->clearFilter();
            break;
            default:
            break;
        }
    }

    public function saveItem($id){
        if($this->roleName == 'buyer'){
            favouriteItem( $this->profile_id, $id, 'profile');
        } else {
            $eventData              = [];
            $eventData['title']     = __('general.error_title');
            $eventData['message']   = __('general.login_error');
            $eventData['type']      = 'error';
            $this->dispatchBrowserEvent('showAlertMessage', $eventData);
        }
    }

    public function clearFilter(){
        $this->keyword = '';
        $this->selected_skills = [];
        $this->seller_type = [];
        $this->english_level = [];
        $this->languages = [];
        $this->seller_min_hr_rate = $this->def_min_hr_rate;
        $this->seller_max_hr_rate = $this->def_max_hr_rate;
        $this->selected_location = '';
    }
}
