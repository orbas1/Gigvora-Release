<?php 

namespace App\Services;

use App\Models\Education;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EducationService {

    public function getEducations($profileId) {
        return Education::where('profile_id', $profileId)
            ->get(['id', 'profile_id', 'deg_title', 'deg_institue_name', 'deg_description', 'address', 'deg_start_date', 'deg_end_date', 'is_ongoing']);
    }

    public function addEducation($eductionData){

        $eductionData['deg_start_date']         = Carbon::parse($eductionData['deg_start_date']);

        if( !empty( $eductionData['is_ongoing'] ) ){
            $eductionData['deg_end_date']       = NULL;
        } else {
            $eductionData['deg_end_date']       = Carbon::parse($eductionData['deg_end_date']);
        }

        $eductionData['is_ongoing']             = $eductionData['is_ongoing'];

        return Education::create($eductionData);
    }

    public function updateEducation($eductionData, $id){    
        return Education::where('id' , $id)->update($eductionData);
    }

    public function deleteEducation($educationId) {
        $profileId = auth()->user()->activeProfile->id;
        
        $education = Education::where('profile_id', $profileId)->where('id', $educationId)->first();
        if ($education) {
            $education->delete();
            return $education;
        } else {
            return false;
        }
    }
}

