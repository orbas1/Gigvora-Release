<?php

namespace App\Http\Livewire\Components;

use Amentotech\LaraPayEase\Facades\PaymentDriver;
use App\Models\UserBillingDetail;
use Livewire\Component;
use App\Models\Country;
use App\Models\UserWallet;
use App\Services\EscrowPayment;
use App\Models\CountryState;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Checkout extends Component
{
    public $profile_id  = '';
    public $wallet_balance  = 0;
    public $use_wallet_bal  = false;
    public $country_id  = '';
    public $state_id    = '';
    public $first_name  = '';
    public $last_name   = '';
    public $company     = '';
    public $address     = '';
    public $stripe_client_secret     = '';
    public $stripe_payment_method     = '';
    public $payouts_settings, $escrowSettings;

    public $phone                           = '';
    public $email                           = '';
    public $city                            = '';
    public $postal_code                     = '';
    public $currency_symbol                 = '';
    public $has_states                      = false;
    public $states                          = [];
    public $payment_method                  = '';
    public $available_payment_methods       = [];

    public function mount(){
        $project_data    = session()->get('project_data');
        $package_data    = session()->get('package_data');
        $gig_data        = session()->get('gig_data');
        if( empty($project_data) && empty($package_data) && empty($gig_data) ){
            return redirect()->route('settings');
        }
        $user                   = getUserRole();
        $this->profile_id       = $user['profileId'];
        $setting                = getTPSetting(['payment'], [ 'payment_methods']);
        $currency               = setting('_general.currency');
        $this->payment_method   = setting('_general.default_payment_method');
        $available_payment_methods    = [];
        $gateways = PaymentDriver::supportedGateways();
        if( !empty($setting['payment_methods']) ){

            $data = unserialize($setting['payment_methods']);

            if( $data['method_type'] == 'others' ){
                if(!empty($data['others'])){
                    foreach($data['others'] as $key => $record ){
                        if(in_array($key, array_keys($gateways))){
                            if($record['status'] == 'on' ) {
                                $this->available_payment_methods[$key] = __('settings.method_'.$key);
                            }
                        }
                    }
                    $wallet = UserWallet::select('id', 'amount')->where('profile_id', $this->profile_id)->first();
                    if( !empty($wallet) ){
                        $this->wallet_balance  = $wallet->amount;
                    }
                }
            }else{
                $this->available_payment_methods['escrow'] = __('settings.method_escrow');
                $this->escrowSettings = $data['escrow'];
            }
        }

        $currency_detail    = !empty($currency) ? currencyList($currency) : array();
        if(!empty($currency_detail)){
            $this->currency_symbol   = $currency_detail['symbol'];
        }
        $this->getBillingInfo();
    }

    public function render(){

        $countries      = Country::select('id','name')->get()->toArray();
        $project_data   = session()->get('project_data');
        $package_data   = session()->get('package_data');
        $gig_data       = session()->get('gig_data');

        // $stripe_intent   = '';

        // if( $this->payment_method == 'stripe' && !$this->securePaymentProcessing){
        //     try {
        //         $intent = auth()->user()->createSetupIntent();
        //         $this->stripe_client_secret = $intent->client_secret;
        //         $this->dispatchBrowserEvent('initializeStripe', ['client_secret' => $this->stripe_client_secret]);
        //     }catch (\Exception $e) {
        //         $this->dispatchBrowserEvent('showAlertMessage', [
        //             'type'          => 'error',
        //             'title'         => __('general.error_title'),
        //             'message'       => $e->getMessage(),
        //             'autoClose'     => 3000,
        //         ]);
        //     }

        // }
        $title = __('general.checkout');
        return view('livewire.components.checkout', compact('countries', 'project_data', 'package_data', 'gig_data'))->extends('layouts.app', compact('title'));
    }

    function updatedCountryId( $id ){

        $getStates = CountryState::select('id','name','country_id')->where('country_id',$id)->get();
        $this->state_id = '';
        if( !$getStates->isEmpty() ){
            $this->states       = $getStates;
            $this->has_states   = true;
            $this->dispatchBrowserEvent('initStateDropdown');
        } else {
            $this->state_id     = null;
            $this->has_states   = false;
            $this->states       = [];
        }
    }

    function updatedPaymentMethod( $value ){

        if( $value == 'stripe' ){
            $this->dispatchBrowserEvent('initializeStripe', ['client_secret' => $this->stripe_client_secret]);
        }
    }

    public function getBillingInfo(){

        $data = UserBillingDetail::where('profile_id', $this->profile_id)->with('states:id,country_id,name')->first();

        if( !empty( $data ) ) {

            $this->country_id           = $data->country_id;
            $this->state_id             = $data->state_id;
            $this->first_name           = $data->billing_first_name;
            $this->last_name            = $data->billing_last_name;
            $this->company              = $data->billing_company;
            $this->address              = $data->billing_address;
            $this->phone                = $data->billing_phone;
            $this->email                = $data->billing_email;
            $this->city                 = $data->billing_city;
            $this->postal_code          = $data->billing_postal_code;
            $this->payouts_settings     = !empty($data->payout_settings) ? unserialize( $data->payout_settings ) : array();
            if( !$data->states->isEmpty() ){
                $this->states       = $data->states;
                $this->has_states   = true;
            }
        }
    }

    public function checkout(){
        $response = isDemoSite();
        $gateways = PaymentDriver::supportedGateways();

        if( $response ){

            $this->dispatchBrowserEvent('showAlertMessage', [
                'type'      => 'error',
                'title'     => __('general.demosite_res_title'),
                'message'   => __('general.demosite_res_txt')
            ]);
            return;
        }

        $validateFields = [
            'payment_method'    => 'required',
            'country_id'        => 'required',
            'first_name'        => 'required',
            'last_name'         => 'required',
            'phone'             => 'required',
            'email'             => 'required|email',
            'postal_code'       => 'required',
            'city'              => 'required',
            'address'           => 'required',
            'company'           => 'nullable',
        ];

        if( !empty($this->states) ){

            $validateFields['state_id'] = 'required';
        }

        $validatedData = $this->validate($validateFields, [

            'payment_method.required'   => __('settings.select_payment_method'),
            'required'                  => __('general.required_field'),
            'email'                     => __('general.invalid_email'),
        ]);

        $validated_data = array_merge($validatedData, $this->extractSessionValues());

        $validated_data = SanitizeArray($validated_data);

        $response = [];
        if( $this->payment_method == 'escrow' ) {
            if( empty($this->escrowSettings) ){

                $this->dispatchBrowserEvent('showAlertMessage', [
                    'title'     => __('general.error_title'),
                    'type'      => 'error',
                    'message'   => __('transaction.escrow_setting_error')
                ]);
                return;
            }
            if( !empty($validated_data['project_data']) ){

                $escrow     =   new EscrowPayment();
                $response   = $escrow->createProjectTransaction( $validated_data );
            }elseif( !empty($validated_data['package_data']) ){

                $escrow_email       = '';
                $escrow_api         = '';
                $escrow             = new EscrowPayment( $escrow_email, $escrow_api );
                $response           = $escrow->createPackageTransaction( $validated_data );

            }elseif( !empty($validated_data['gig_data']) ){

                $escrow    = new EscrowPayment();
                $response  = $escrow->createGigOrderTransaction( $validated_data );
            }
            $this->handleCompleteTransaction($response);
        } elseif( array_key_exists($this->payment_method, $gateways) ) {
            $validated_data['user']   = auth()->user();
            $validated_data['stripe_payment_method']    = $this->stripe_payment_method;
            $validated_data['use_wallet_bal']           = $this->use_wallet_bal;
            $validated_data['wallet_balance']           = $this->wallet_balance;
            $validated_data['payment_method']           = $this->payment_method;

            $paymentService  =   new PaymentService();
            if( !empty($validated_data['project_data']) ){
                $paymentService->createProjectTransaction($validated_data);
            }elseif( !empty($validated_data['gig_data']) ){
                $paymentService->createGigOrderTransaction($validated_data);
                $validated_data['return_url'] = !empty($response['return_url']) ? $response['return_url'] : route('dashboard');
            }elseif( !empty($validated_data['package_data']) ){
                $paymentService->createPackageTransaction($validated_data);
            }
        }
    }

    protected function extractSessionValues() {
        $project_data = session()->get('project_data');
        $package_data = session()->get('package_data');
        $gig_data     = session()->get('gig_data');

        if( !empty($project_data) ){

            $validated_data['creator_id']           = $this->profile_id;
            $validated_data['project_id']           = $project_data['project_id'];
            $validated_data['proposal_id']          = $project_data['proposal_id'];
            $validated_data['milestone_id']         = !empty($project_data['milestone_id']) ? $project_data['milestone_id'] : 0;
            $validated_data['timecard_id']          = !empty($project_data['timecard_id']) ? $project_data['timecard_id'] : 0;
            $validated_data['return_url']           = route('project-activity', ['slug' => $project_data['project_slug'], 'id'=> $project_data['proposal_id']]);

        }elseif( !empty($package_data) ){

            $validated_data['creator_id']           = $this->profile_id;
            $validated_data['package_id']           = $package_data['package_id'];
            $validated_data['package_title']        = $package_data['package_title'];
            $validated_data['package_price']        = $package_data['package_price'];
            $validated_data['return_url']           = route('packages');

        }elseif( !empty($gig_data) ){

            $validated_data['creator_id']           = $this->profile_id;
            $validated_data['gig_id']               = $gig_data['gig_id'];
            $validated_data['gig_title']            = $gig_data['gig_title'];
            $validated_data['gig_author']           = $gig_data['gig_author'];
            $validated_data['plan_id']              = $gig_data['plan_id'];
            $validated_data['plan_type']            = $gig_data['plan_type'];
            $validated_data['delivery_time']        = $gig_data['delivery_time'];
            $validated_data['plan_price']           = $gig_data['plan_price'];
            $validated_data['gig_addons']           = $gig_data['gig_addons'];
            $validated_data['downloadable']         = $gig_data['downloadable'];
            $validated_data['return_url']           = route('dashboard');
        }

        $validated_data['project_data']   = $project_data;
        $validated_data['package_data']   = $package_data;
        $validated_data['gig_data']       = $gig_data;

        return $validated_data;
    }

    protected function extractSessionCacheValues($transaction) {
        $paymentService  =   (new PaymentService())->getTransactionItemDetail($transaction);
        if ($transaction->payment_type == 'package') {
            $package_data = [
                'creator_id'      => $transaction->creator_id,
                'package_id'      => $paymentService['package_id'],
                'package_title'   => $paymentService['title'],
                'package_price'   => $paymentService['package_price'],
                'return_url'      => $paymentService['return_url'],
            ];
            return array_merge($package_data, ['package_data' => $package_data]);
        } elseif ($transaction->payment_type == 'project') {
            $project_data = [
                'creator_id'      => $transaction->creator_id,
                'project_id'      => $paymentService['project_id'],
                'proposal_id'     => $paymentService['proposal_id'],
                'milestone_id'    => $paymentService['milestone_id'],
                'timecard_id'     => $paymentService['timecard_id'],
                'return_url'      => $paymentService['return_url'],
            ];
            return array_merge($project_data, ['project_data' => $project_data]);
        } else {
            $gig_data = [
                'creator_id'      => $transaction->creator_id,
                'gig_id'          => $paymentService['gig_id'],
                'gig_title'       => $paymentService['gig_title'],
                'gig_author'      => $paymentService['gig_author'],
                'plan_id'         => $paymentService['plan_id'],
                'plan_type'       => $paymentService['plan_type'],
                'delivery_time'   => $paymentService['delivery_time'],
                'plan_price'      => $paymentService['plan_price'],
                'gig_addons'      => $paymentService['gig_addons'],
                'downloadable'    => $paymentService['downloadable'],
                'return_url'      => $paymentService['return_url'],
            ];
            return array_merge($gig_data, ['gig_data' => $gig_data]);
        }
    }

    protected function handleCompleteTransaction($response, $transaction = null){
        if( !empty($response) && $response['type'] == 'success' ){
            session()->forget('package_data');
            session()->forget('project_data');
            session()->forget('gig_data');

            // Cache::forget('transaction-session-'.$transaction?->id);
            if(!empty($response['flash_message'])){
                return redirect()->intended($response['return_url'])->with('payment_success',$response['flash_message']);
            }
            return redirect()->intended( $response['return_url'] );
        }else{

            $eventData = array();
            $eventData['title']     = __('general.error_title');
            $eventData['message']   =  !empty($response['message']) ? $response['message'] : __('settings.wrong_msg');
            $eventData['type']      = 'error';
            $this->dispatchBrowserEvent('showAlertMessage', $eventData);
        }
    }

    protected function paymentSuccess($paymentData){
        $this->mount();
        $transaction = Transaction::with('TransactionDetail')->whereKey($paymentData['data']['order_id'])->first();

        if (empty($transaction)) {
             $eventData = array();
            $eventData['title']     = __('general.error_title');
            $eventData['message']   =  !empty($response['message']) ? $response['message'] : __('settings.wrong_msg');
            $eventData['type']      = 'error';
            $this->dispatchBrowserEvent('showAlertMessage', $eventData);
            return;
        }

        $transaction->update(['trans_ref_no' => $paymentData['data']['transaction_id']]);

        $validatedData = [
            'payment_method'    => $transaction->payment_method,
        ];
        $extractedSessions = $this->extractSessionValues();

        if( ($transaction->payment_type == 'gig' && empty($extractedSessions['gig_data'])) ||
            ($transaction->payment_type == 'project' && empty($extractedSessions['project_data'])) ||
            ($transaction->payment_type == 'package' && empty($extractedSessions['package_data'])) ) {
            $extractedSessions = $this->extractSessionCacheValues($transaction);
        }

        $params = array_merge($validatedData, $extractedSessions);
        $params['user']   = auth()->user();
        $params['use_wallet_bal']           = $transaction->TransactionDetail->use_wallet_bal > 0;
        $params['wallet_balance']           = $this->wallet_balance;
        $params['payment_method']           = $transaction->payment_method;

        if (!empty($this->profile_id)) {
            $params['creator_id']               = $this->profile_id;
        }

        $paymentService  =   new PaymentService();
        if( !empty($params['project_data']) ){
            $response = $paymentService->createProjectTransaction($params, $transaction);
        }elseif( !empty($params['gig_data']) ){
            $response = $paymentService->createGigOrderTransaction($params, $transaction);
            $response['return_url'] = !empty($response['return_url']) ? $response['return_url'] : route('dashboard');
        }elseif( !empty($params['package_data']) ){
            $params['package_id']       = $transaction->TransactionDetail->type_ref_id;
            $params['package_price']    = $transaction->TransactionDetail->amount;
            $response = $paymentService->createPackageTransaction($params, $transaction);
        }
        return $this->handleCompleteTransaction($response, $transaction);
    }

    public function payfastWebhook(Request $request){
        header('HTTP/1.0 200 OK');
        flush();
        $gatewayObj = getGatewayObject('payfast');
        if(!empty($gatewayObj)) {
            $paymentData = $gatewayObj->paymentResponse($request->all());
            if (!empty($paymentData) && $paymentData['status'] == Response::HTTP_OK) {
                $this->paymentSuccess($paymentData);
            }
        }
    }

    public function success(Request $request) {
        if($request['payment_method'] == 'payfast') {
            return $this->handleCompleteTransaction([
                'type' => 'success',
                'return_url' => route('invoices'),
                'flash_message' => __('settings.payment_success_msg')
            ]);
        } else {
            $gatewayObj = getGatewayObject($request['payment_method']);
            if(!empty($gatewayObj)) {
                $paymentData = $gatewayObj->paymentResponse($request->all());
                if (!empty($paymentData) && $paymentData['status'] == Response::HTTP_OK) {
                    return $this->paymentSuccess($paymentData);
                } else {
                    return redirect(route('invoices'))->with('payment_cancel',__('general.payment_cancelled_desc'));
                }
            }

        }
    }

}
