<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gig\GigCollection;
use App\Http\Resources\Gig\GigDetailResource;
use App\Http\Resources\Gig\GigResource;
use App\Services\GigService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GigController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        if(!empty(request()->bearerToken())){
            $this->middleware('auth:sanctum')->only(['index', 'popularGigs','gigDetail']);
        }
    }

    public function index(Request $request)
    {
        $filters = [
            'min_price' => $request->min_price ?? '',
            'max_price' => $request->max_price ?? '',
            'keyword' => $request->keyword ?? '',
            'selected_location' => $request->location ?? '',
            'selected_category' => $request->category ?? '',
            'per_page' =>  $request->per_page ?? setting('_general.per_page_record'),
        ];

        $gigs = (new GigService)->getGigs($filters, $request->sort_by ?? 'date_desc');

        return $this->success(new GigCollection($gigs), __('gig.gigs_list_pagination'));
    }

    public function gigDetail(int $id)
    {
        $data = (new GigService)->getDetail($id);
        if($data){
            return $this->success(new GigDetailResource($data));
        } else {
            return $this->error( __('settings.wrong_msg'));
        }
    }

    public function popularGigs(Request $request)
    {
        $filters = [
            'per_page' => $request->per_page ?? '',
        ];
        $gigs = (new GigService)->getGigs($filters, 'order_desc');
        return $this->success(GigResource::collection($gigs), __('gig.gigs_list'));
    }
}
