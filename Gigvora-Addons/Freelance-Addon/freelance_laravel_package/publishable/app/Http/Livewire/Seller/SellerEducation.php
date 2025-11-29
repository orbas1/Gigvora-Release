<?php

namespace App\Http\Livewire\Seller;

use Livewire\Component;
use App\Models\Education;
use App\Services\EducationService;

class SellerEducation extends Component
{

    public $page_loaded = false; 
    public $user_profile_id  = '';

    public function render()
    {
        $educations = [];
        if( $this->page_loaded ){
            $educations = (new EducationService())->getEducations($this->user_profile_id);
        }
        
        return view('livewire.seller.seller-education',compact('educations'));
    }

    public function mount($user_profile_id) {
        $this->user_profile_id = $user_profile_id;
    }

    public function loadEducations(){
        $this->page_loaded = true;
    }
}
