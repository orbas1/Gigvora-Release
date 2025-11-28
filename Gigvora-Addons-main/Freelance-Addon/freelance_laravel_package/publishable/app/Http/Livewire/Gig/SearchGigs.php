<?php

namespace App\Http\Livewire\Gig;

use App\Models\Gig\Gig;
use App\Models\Gig\GigPlan;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FavouriteItem;
use App\Services\GigService;

class SearchGigs extends Component
{
    use WithPagination;
    public $selected_category = [];
    public $keyword              = '';
    public $min_price           = '';
    public $max_price           = '';
    public $selected_location   = '';
    public $currency_symbol     = '';
    public $per_page            = '';
    public $profile_id          = '';
    public $sort_by             = '';
    protected $listeners        = ['ApplySearchFilter' => 'SearchFilter']; 
    public $page_loaded         = false; 
    public $roleName            = '';
    public $address_format      = ''; 
    public $view_type           = 'grid';
    protected $queryString = [
        'keyword'       => ['except' => ''],
        'min_price'     => ['except' => ''],
        'max_price'     => ['except' => ''],
    ];

    public function mount( $view_type = 'grid', $selected_category = '' ){
        $this->view_type = $view_type;

        if( !empty($selected_category) ){
            $this->selected_category = $selected_category;
        }
        $user = getUserRole();
        $this->profile_id       = !empty($user['profileId']) ? $user['profileId'] : '';
        $this->roleName         = !empty($user['roleName']) ? $user['roleName'] : '';

        $currency               = setting('_general.currency');
        $per_page_record        = setting('_general.per_page_record');
        $address_format         = setting('_general.address_format');

        $this->per_page         = !empty( $per_page_record ) ? $per_page_record : 10;
        $this->address_format   = !empty( $address_format ) ? $address_format : 'state_country';
        $currency_detail        = !empty( $currency)    ? currencyList($currency) : array();
        if( !empty($currency_detail['symbol']) ){
            $this->currency_symbol = $currency_detail['symbol'];
        }
    }

    public function render(){

        $gigs       = [];
        if(!empty($this->page_loaded)){

            $this->sort_by = !empty($this->sort_by) ? $this->sort_by : 'date_desc';

            $filters = [ 
                'min_price' => $this->min_price,
                'max_price' => $this->max_price,
                'keyword' => $this->keyword,
                'selected_location' => $this->selected_location,
                'selected_category' => $this->selected_category,
                'per_page' => 12,
            ];

            $gigs = (new GigService)->getGigs($filters, $this->sort_by);
        }

        return view('livewire.gig.'.$this->view_type, compact('gigs'));
    }

    
    public function loadGigs(){
        $this->page_loaded = true;
    }

    public function SearchFilter($data){
        $type = !empty($data['type']) ? $data['type'] : '';
        
        if(in_array($type, ['keyword', 'category', 'location', 'pricerange', 'clearfilter'])){
            $this->resetPage();
        }
        switch($type){
            case 'keyword':
                $this->keyword = !empty($data['keyword']) ? $data['keyword'] : '';
            break;
            case 'category':
                $this->selected_category = !empty($data['category']) ? $data['category'] : '';
            break;
            case 'location':
                $this->selected_location = !empty($data['location']) ? $data['location'] : '';
            break;
            case 'pricerange':
                $this->min_price = !empty($data['min_price']) ? $data['min_price'] : '';
                $this->max_price = !empty($data['max_price']) ? $data['max_price'] : '';
            break;
            case 'sortby':
                $this->sort_by = !empty($data['sort_by']) ? $data['sort_by'] : '';
            case 'clearfilter':
                $this->keyword   = '';
                $this->selected_category  = '';
                $this->selected_location    = '';
                $this->min_price            = '';
                $this->max_price            = '';
            break;

            default:

            break;
        }
    }

    public function saveItem($gig_id){
        if($this->roleName == 'buyer'){
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
