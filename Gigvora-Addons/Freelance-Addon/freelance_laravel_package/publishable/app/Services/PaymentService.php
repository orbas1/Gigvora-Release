<?php

namespace App\Services;

use Amentotech\LaraPayEase\Facades\PaymentDriver;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\Profile;
use App\Models\Project;
use App\Events\NotifyUser;
use App\Models\UserWallet;
use App\Models\AdminPayout;
use App\Models\Transaction;
use App\Models\CountryState;
use App\Models\Gig\Gig;
use App\Models\Gig\GigOrder;
use App\Models\Package\Package;
use App\Models\UserWalletDetail;
use App\Models\Proposal\Proposal;
use App\Models\TransactionDetail;
use App\Models\Seller\SellerPayout;
use App\Models\Package\PackageSubscriber;
use App\Models\Proposal\ProposalTimecard;
use App\Models\Proposal\ProposalMilestone;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    public function createProjectTransaction( $params, $transaction = null ){

        $response = array();
        if (empty($transaction)) {
            $country_detail       = Country::select('name')->where('id', $params['country_id'])->first();
            $buyer_country_full   = $country_detail->name;
            $buyer_state_full = '';

            if( !empty($params['state_id']) ){
                $state_detail            = CountryState::select('name')->where('id', $params['state_id'])->first();
                if( !empty($state_detail) ){
                    $buyer_state_full        = $state_detail->name;
                }
            }
        }

        $proposal_detail  = Proposal::with('proposalAuthor:id,first_name,last_name,user_id')->find($params['proposal_id']);
        $project          = Project::select('id', 'status', 'project_hiring_seller','slug', 'project_title', 'project_type')->find($params['project_id']);

        $price                  = $proposal_detail->proposal_amount;
        $commission_amount      = !empty($proposal_detail->commission_amount) ? $proposal_detail->commission_amount : 0 ;

        if( $proposal_detail->payout_type == 'fixed' ){

            $transaction_type = '2';
            $type_ref_id = $params['proposal_id'];
            $commission_amount =  round($commission_amount, 2);
        }elseif( $proposal_detail->payout_type == 'milestone' ){

            $milestone_detail   = ProposalMilestone::select('title', 'price')->find($params['milestone_id']);
            $price              = $milestone_detail->price;

            if( $commission_amount > 0 ){

                $milestone_percentage   = ($milestone_detail->price / $proposal_detail->proposal_amount) * 100;
                $commission_amount      = $commission_amount * ($milestone_percentage / 100);
                $commission_amount      =  round($commission_amount, 2);
            }

            $transaction_type = '1';
            $type_ref_id = $params['milestone_id'];
        }else{

            $timecard_detail   = ProposalTimecard::select('title', 'price', 'total_time')->find($params['timecard_id']);
            $price              = $timecard_detail->price;

            if( $commission_amount > 0 ){

                $time = explode(':', $timecard_detail->total_time);
                $hours = $time[0];
                $mins_cost = 0;
                if( isset($time[1]) && $time[1] > 0 ){
                    $per_min_amount = $commission_amount/60;
                    $mins_cost =  $per_min_amount *  $time[1];
                }
                $commission_amount =  ($commission_amount * $hours) + $mins_cost;
                $commission_amount =  round($commission_amount, 2);
            }
            $transaction_type   = '3';
            $type_ref_id        = $params['timecard_id'];
        }

        $seller_amount = round( $price - $commission_amount, 2);

        try{
            $used_wallet_amt = 0;
            $stripe_price = $price;
            $charge_from_stripe = true;
            if( $params['use_wallet_bal'] ){

                if( $params['wallet_balance'] >= $price ){
                    $remaining_wallet_bal   = $params['wallet_balance'] - $price;
                    $used_wallet_amt        = $price;
                    $charge_from_stripe     = false;
                }else{
                    $used_wallet_amt        = $params['wallet_balance'];
                    $stripe_price           = $price - $params['wallet_balance'];
                    $remaining_wallet_bal   = 0;
                }
            }

            $transaction_params  = [
                'amount'                => $price,
                'used_wallet_amt'       => $used_wallet_amt,
                'buyer_country_full'    => $buyer_country_full ?? '',
                'buyer_state_full'      => $buyer_state_full ?? '',
                'transaction_type'      => $transaction_type,
                'type_ref_id'           => $type_ref_id,
                'seller_amount'         => $seller_amount,
                'commission_amount'     => $commission_amount,
                'remaining_wallet_bal'  => $remaining_wallet_bal ?? 0,
                'charge_from_stripe'    => $charge_from_stripe
            ];

            if( $charge_from_stripe && empty($transaction) ){

                $transaction = $this->addTransaction($params['creator_id'], 'project', array_merge($transaction_params, $params));
                Cache::put('transaction-session-'.$transaction->id, array_merge($transaction_params, $params), now()->addMinutes(30));

                $ipnUrl = PaymentDriver::getIpnUrl($params['payment_method']);

                session()->put([
                    'payment_data'  => [
                        'amount'        => $stripe_price ,
                        'title'         => $project->project_title,
                        'description'   => __('transaction.transaction_desc'),
                        'ipn_url'       => !empty($ipnUrl) ? route($ipnUrl, ['payment_method' => $params['payment_method']]) : url('/'),
                        'order_id'      => $transaction->id,
                        'track'         => Str::random(36),
                        'cancel_url'    => route('freelance.checkout.cancel'),
                        'success_url'   => route('freelance.dashboard'),
                        'email'         => $params['email'],
                        'name'          => $params['first_name'],
                        'payment_type'  => 'project',
                    ]
                ]);
                return redirect()->route('freelance.payments.process', ['gateway' => $params['payment_method']]);

            }

            if( !$charge_from_stripe || !empty($transaction) ){

                if ( empty($transaction) ) {
                    $transaction = $this->addTransaction($params['creator_id'], 'project', array_merge($transaction_params, $params));
                }

                $this->processProjectTransaction($transaction, $project, $proposal_detail, array_merge($transaction_params, $params));

                $response['type']        = 'success';
                $response['return_url']  = $params['return_url'];
            }

        }
        catch (\Exception $exception) {
            $response['type']  = 'error';
            $response['message']  = $exception->getMessage();
        }
        return $response;
    }

    protected function processProjectTransaction($transaction, $project, $proposal_detail, $params)
    {
        if ($transaction->status == 'pending') {
            $transaction->update(['status' => 'processed']);
        }

        SellerPayout::create([
            'transaction_id'       => $transaction->id,
            'project_id'           => $params['project_id'],
            'seller_id'            => $proposal_detail->author_id,
            'seller_amount'        => $params['seller_amount'],
            'admin_commission'     => $params['commission_amount'],
        ]);

        if( $params['use_wallet_bal'] ){

            UserWallet::updateOrCreate(['profile_id' => $params['creator_id']], [
                'profile_id'  => $params['creator_id'],
                'amount'      => $params['remaining_wallet_bal'],
            ]);
        }

        $proposal_id = $params['type_ref_id'];

        if( $params['transaction_type'] == 1 ){

            $proposal_milestone = ProposalMilestone::select('id', 'proposal_id','title')->find($params['type_ref_id']);
            $proposal_milestone->update(['status' => 'processed']);
            $proposal_id = $proposal_milestone->proposal_id;

            // send email to seller when escrow milestone
            $eventData = array();
            $eventData['milestone_title'] = $proposal_milestone->title;
            $eventData['email_type']      = 'escrow_milestone';
            $eventData['proposal_id']     = $proposal_id;
            event(new NotifyUser($eventData));

        }elseif( $params['transaction_type'] == 3  ){

            $proposal_timecard = ProposalTimecard::select('id', 'proposal_id','title')->find($params['type_ref_id']);
            $proposal_timecard->update(['status' => 'completed']);
            $proposal_id        = $proposal_timecard->proposal_id;
            $transaction->update(['status' => 'completed']);

            if( $params['commission_amount'] > 0 ){

                AdminPayout::updateOrCreate(['transaction_id' => $transaction->id], [
                    'transaction_id'    => $transaction->id,
                    'amount'            => $params['commission_amount'],
                ]);
            }

            $user_wallet        = UserWallet::where('profile_id' , $proposal_detail->proposalAuthor->id)->first();
            $wallet_profile_id  = !empty($user_wallet) ? $user_wallet->profile_id : 0;
            $wallet_amount      = !empty($user_wallet) ? $user_wallet->amount : 0;
            $wallet_amount      += $params['seller_amount'];

            $wallet = UserWallet::updateOrCreate(['profile_id' => $wallet_profile_id], [
                'profile_id'  => $proposal_detail->proposalAuthor->id,
                'amount'      => $wallet_amount,
            ]);

            UserWalletDetail::create([
                'transaction_id'    => $transaction->id,
                'wallet_id'         => $wallet->id,
                'amount'            => $params['seller_amount'],
            ]);

            // send email to seller timecard completed
            $eventData = array();
            $eventData['timecard_title']  = $proposal_timecard->title;
            $eventData['email_type']      = 'timecard_accepted';
            $eventData['proposal_id']     = $proposal_id;
            event(new NotifyUser($eventData));
        }

        if( $proposal_detail->status == 'publish' ){ // send email && notification

            $proposal_detail->update(['status'=> 'hired']);

            $eventData                              = array();
            $eventData['project_title']             = $project->project_title;
            $eventData['user_name']                 = $proposal_detail->proposalAuthor->full_name;
            $eventData['user_id']                   = $proposal_detail->proposalAuthor->user_id;
            $eventData['email_type']                = 'proposal_request_accepted';
            $eventData['project_activity_link']     = route('freelance.projects.activity', ['slug' => $project->slug, 'id'=> $proposal_detail->id]);
            event(new NotifyUser($eventData));
        }

        $total_hired_proposal   = Proposal::where('project_id', $project->id)->whereIn('status', array('hired', 'completed', 'refunded'))->count('id');

        if( $project->status == 'publish' && $project->project_hiring_seller == $total_hired_proposal ){
            $project->update(['status' => 'hired']);
        }
    }

    public function createPackageTransaction( $params, $transaction = null )
    {
        $response = array();
        if (empty($transaction)) {
            $country_detail       = Country::select('name')->where('id', $params['country_id'])->first();
            $buyer_country_full   = $country_detail->name;
            $buyer_state_full = '';

            if( !empty($params['state_id']) ){
                $state_detail            = CountryState::select('name')->where('id', $params['state_id'])->first();
                if( !empty($state_detail) ){
                    $buyer_state_full        = $state_detail->name;
                }
            }
        }

        $amount  = $params['package_price'];
        try{

            $used_wallet_amt = 0;
            $stripe_price = $amount;
            $charge_from_stripe = true;
            if( $params['use_wallet_bal'] ){

                if( $params['wallet_balance'] >= $amount ){
                    $remaining_wallet_bal   = $params['wallet_balance'] - $amount;
                    $used_wallet_amt        = $amount;
                    $charge_from_stripe     = false;
                }else{
                    $used_wallet_amt        = $params['wallet_balance'];
                    $stripe_price           = $amount - $params['wallet_balance'];
                    $remaining_wallet_bal   = 0;
                }
            }
            $transaction_params  = [
                'amount'                => $amount,
                'used_wallet_amt'       => $used_wallet_amt,
                'buyer_country_full'    => $buyer_country_full ?? '',
                'buyer_state_full'      => $buyer_state_full ?? '',
                'transaction_type'      => '0',
                'type_ref_id'           => $params['package_id'],
                'remaining_wallet_bal'  => $remaining_wallet_bal ?? 0,
                'charge_from_stripe'    => $charge_from_stripe
            ];

            if( $charge_from_stripe && empty($transaction) ){

                $transaction = $this->addTransaction($params['creator_id'], 'package', array_merge($transaction_params, $params));
                Cache::put('transaction-session-'.$transaction->id, array_merge($transaction_params, $params), now()->addMinutes(30));

                $ipnUrl = PaymentDriver::getIpnUrl($params['payment_method']);

                session()->put([
                    'payment_data'  => [
                        'amount'        => $stripe_price ,
                        'title'         => __('Package'),
                        'description'   => !empty($params['package_title']) ? $params['package_title'] : __('transaction.transaction_desc'),
                        'ipn_url'       => !empty($ipnUrl) ? route($ipnUrl, ['payment_method' => $params['payment_method']]) : url('/'),
                        'order_id'      => $transaction->id,
                        'track'         => Str::random(36),
                        'cancel_url'    => route('freelance.checkout.cancel'),
                        'success_url'   => route('freelance.dashboard'),
                        'email'         => $params['email'],
                        'name'          => $params['first_name'],
                        'payment_type'  => 'package',
                    ]
                ]);
                return redirect()->route('freelance.payments.process', ['gateway' => $params['payment_method']]);
            }

            if( !$charge_from_stripe || !empty($transaction)){
                if(empty($transaction)){
                    $transaction = $this->addTransaction($params['creator_id'], 'package', array_merge($transaction_params, $params));
                }
                $this->processPackageTransaction($transaction, array_merge($transaction_params, $params));
                $response['type']        = 'success';
                $response['return_url']  = $params['return_url'];
            }
        }
        catch (\Exception $exception) {
            $response['type']  = 'error';
            $response['message']  = $exception->getMessage();
            Log::info($exception);
        }
        return $response;
    }

    protected function processPackageTransaction($transaction, $params) {
        if ($transaction->status == 'pending') {
            $transaction->update(['status' => 'processed']);
        }

        if( $params['use_wallet_bal'] ){

            UserWallet::updateOrCreate(['profile_id' => $params['creator_id']], [
                'profile_id'  => $params['creator_id'],
                'amount'      => $params['remaining_wallet_bal'],
            ]);
        }

        $package_detail = Package::with('package_role:id,name')->find( $params['package_id'] );

        if( !empty($package_detail) ){

            $options = unserialize( $package_detail->options );
            if( $options['type'] == 'year' ){
                $expiry_date =  Carbon::now()->addYear($options['duration'])->format('Y-m-d H:i:s');
            }elseif( $options['type'] == 'month' ){
                $expiry_date =  Carbon::now()->addMonth($options['duration'])->format('Y-m-d H:i:s');
            }else{
                $expiry_date =  Carbon::now()->addDays($options['duration'])->format('Y-m-d H:i:s');
            }

            if( $package_detail->package_role->name == 'buyer' ){

                $package_options = array(
                    'type'          => $options['type'],
                    'duration'      => $options['duration'],
                    'allow_quota'   => array(
                        'posted_projects'           => $options['posted_projects'],
                        'featured_projects'         => $options['featured_projects'],
                        'project_featured_days'     => $options['project_featured_days'],
                    ),
                    'rem_quota'  => array(
                        'posted_projects'           => $options['posted_projects'],
                        'featured_projects'         => $options['featured_projects'],
                    )
                );
            }else{

                $package_options = array(
                    'type'          => $options['type'],
                    'duration'      => $options['duration'],
                    'allow_quota'   => array(
                        'credits'               => $options['credits'],
                        'profile_featured_days' => $options['profile_featured_days'],
                    ),
                    'rem_quota' => array(
                        'credits'    => $options['credits'],
                    )
                );

                $featured_expiry = null;
                if( !empty($options['profile_featured_days']) ){

                    $profile_featured_days = $options['profile_featured_days'];
                    $featured_expiry  = Carbon::now()->addDays($profile_featured_days)->format('Y-m-d H:i:s');
                }
                $profile = Profile::where(['id'=> $transaction->creator_id]);
                $profile->update(['is_featured'=> 1, 'featured_expiry' => $featured_expiry]);
            }

            PackageSubscriber::where('subscriber_id', $transaction->creator_id)->update(['status' => 'expired']);

            $package_subscriber = PackageSubscriber::create([
                'subscriber_id'     => $transaction->creator_id,
                'package_id'        => $params['package_id'],
                'package_price'     => $package_detail->price,
                'package_options'   => serialize( $package_options),
                'package_expiry'    => $expiry_date,
            ]);

            AdminPayout::updateOrCreate(['transaction_id' => $transaction->id], [
                'transaction_id'    => $transaction->id,
                'amount'            => $params['amount'],
            ]);

            // notify email to admin and purchaser(seller and buyer)
            $eventData                           = array();
            $eventData['pckg_subscriber_id']     = $package_subscriber->id;
            $eventData['email_type']             = 'package_purchase';
            event(new NotifyUser($eventData));
        }
    }

    public function createGigOrder($params)
    {
        return GigOrder::create([
            'author_id'             => $params['creator_id'],
            'gig_id'                => $params['gig_id'],
            'plan_type'             => $params['plan_type'],
            'plan_amount'           => $params['plan_price'],
            'gig_features'          => null,
            'gig_addons'            => !empty($params['gig_addons']) ? serialize($params['gig_addons']) : null,
            'downloadable'          => !empty($params['downloadable']) ? $params['downloadable'] : null,
            'gig_delivery_days'     => $params['delivery_time'],
            'status'                => 'draft',
        ]);
    }

    public function createGigOrderTransaction( $params, $transaction = null )
    {

        $response = array();

        $amount  = $params['plan_price'];
        if( !empty($params['gig_addons']) ){
            foreach($params['gig_addons'] as $single){
                $amount +=   $single['price'];
            }
        }

        if (empty($transaction)) {
            $country_detail       = Country::select('name')->where('id', $params['country_id'])->first();
            $buyer_country_full   = $country_detail->name;
            $buyer_state_full = '';

            if( !empty($params['state_id']) ){
                $state_detail            = CountryState::select('name')->where('id', $params['state_id'])->first();
                if( !empty($state_detail) ){
                    $buyer_state_full        = $state_detail->name;
                }
            }
        }

        try{

            $used_wallet_amt = 0;
            $charge_from_stripe = true;
            $stripe_price = $amount;
            if( $params['use_wallet_bal'] ){

                if( $params['wallet_balance'] >= $amount ){
                    $remaining_wallet_bal   = $params['wallet_balance'] - $amount;
                    $used_wallet_amt        = $amount;
                    $charge_from_stripe     = false;
                }else{
                    $used_wallet_amt        = $params['wallet_balance'];
                    $stripe_price           = $amount - $params['wallet_balance'];
                    $remaining_wallet_bal   = 0;
                }
            }

            $transaction_params  = [
                'amount'                => $amount,
                'used_wallet_amt'       => $used_wallet_amt,
                'buyer_country_full'    => $buyer_country_full ?? '',
                'buyer_state_full'      => $buyer_state_full ?? '',
                'transaction_type'      => '4',
                'remaining_wallet_bal'  => $remaining_wallet_bal ?? 0,
                'charge_from_stripe'    => $charge_from_stripe
            ];

            if( $charge_from_stripe && empty($transaction) ){

                //$orderid, create gig order with draft status
                $order = $this->createGigOrder($params);
                if(!empty($order)){
                    $transaction_params['type_ref_id']  = $order->id;
                }

                $transaction = $this->addTransaction($params['creator_id'], 'gig', array_merge($transaction_params, $params));
                Cache::put('transaction-session-'.$transaction->id, array_merge($transaction_params, $params), now()->addMinutes(30));

                $ipnUrl = PaymentDriver::getIpnUrl($params['payment_method']);

                session()->put([
                    'payment_data'  => [
                        'amount'        => $stripe_price,
                        'title'         => $params['gig_title'],
                        'description'   => __('transaction.transaction_desc'),
                        'ipn_url'       => !empty($ipnUrl) ? route($ipnUrl, ['payment_method' => $params['payment_method']]) : url('/'),
                        'order_id'      => $transaction->id,
                        'track'         => Str::random(36),
                        'cancel_url'    => route('freelance.checkout.cancel'),
                        'success_url'   => route('freelance.dashboard'),
                        'email'         => $params['email'],
                        'name'          => $params['first_name'],
                        'payment_type'  => 'gig',
                    ]
                ]);
                return redirect()->route('freelance.payments.process', ['gateway' => $params['payment_method']]);
            }

            if( !$charge_from_stripe || !empty($transaction)){

                if(empty($transaction)){
                    $order = $this->createGigOrder($params);
                    if(!empty($order)){
                        $transaction_params['type_ref_id']  = $order->id;
                    }
                    $transaction = $this->addTransaction($params['creator_id'], 'gig', array_merge($transaction_params, $params));
                }
                $response['type']        = 'success';
                $response['return_url'] = $this->processGigTransaction($transaction, array_merge($transaction_params, $params));
            }

        }
        catch (\Exception $exception) {
            $response['type']  = 'error';
            $response['message']  = $exception->getMessage();
            Log::info($exception);
        }
        return $response;
    }

    protected function processGigTransaction($transaction, $params) {
        $order_id = $transaction->TransactionDetail->type_ref_id;

        if ($transaction->status == 'pending') {
            $transaction->update(['status' => 'processed']);
        }

        SellerPayout::create([
            'transaction_id'       => $transaction->id,
            'project_id'           => null,
            'gig_id'               => $params['gig_id'],
            'seller_id'            => $params['gig_author'],
            'seller_amount'        => $params['amount'],
            'admin_commission'     => 0,
        ]);

        $gig_order = GigOrder::select('id','author_id','gig_id')->with([
            'orderAuthor:id,user_id,first_name,last_name',
            'gig:id,title,slug,author_id',
            'gig.gigAuthor:id,user_id,first_name,last_name'
        ])->find($order_id);

        if( $params['use_wallet_bal'] ){

            UserWallet::updateOrCreate(['profile_id' => $params['creator_id']], [
                'profile_id'  => $params['creator_id'],
                'amount'      => $params['remaining_wallet_bal'],
            ]);
        }

        $gig_title      = $gig_order->gig->title;
        $gig_slug       = $gig_order->gig->slug;
        $seller_id      = $gig_order->gig->gigAuthor->user_id;
        $gig_author     = $gig_order->gig->gigAuthor->full_name;
        $order_author   = $gig_order->orderAuthor->full_name;
        $buyer_id       = $gig_order->orderAuthor->user_id;

        $gig_order->update([
            'status'                => 'hired',
            'gig_start_time'        => date('Y-m-d H:i:s')
        ]);

        $eventData                  = array();
        $eventData['gig_title']     = $gig_title;
        $eventData['buyer_id']      = $buyer_id;
        $eventData['seller_id']     = $seller_id;
        $eventData['gig_author']    = $gig_author;
        $eventData['order_author']  = $order_author;
        $eventData['email_type']    = 'post_gig_order';

        event(new NotifyUser($eventData));

        return route('freelance.gigs.activity', ['slug' => $gig_slug, 'order_id' => $order_id]);
    }

    protected function addTransaction($creatorId, $type, $params, $status = 'pending')
    {
        $transaction = Transaction::create([
            'creator_id'       => $creatorId,
            'trans_ref_no'     => null,
            'payment_type'     => $type,
            'payment_method'   => $params['payment_method'],
            'status'           => $status
        ]);

        TransactionDetail::create([
            'transaction_id'            => $transaction->id,
            'amount'                    => ($params['amount'] - $params['used_wallet_amt']),
            'used_wallet_amt'           => $params['used_wallet_amt'],
            'currency'                  => setting('_general.currency'),
            'payer_first_name'          => $params['first_name'],
            'payer_last_name'           => $params['last_name'],
            'payer_company'             => $params['company'],
            'payer_country'             => $params['buyer_country_full'],
            'payer_state'               => $params['buyer_state_full'],
            'payer_postal_code'         => $params['postal_code'],
            'payer_address'             => $params['address'],
            'payer_city'                => $params['city'],
            'payer_phone'               => $params['phone'],
            'payer_email'               => $params['email'],
            'transaction_type'          => $params['transaction_type'],
            'type_ref_id'               => $params['type_ref_id'],
        ]);

        return $transaction;
    }

    public function getTransactionItemDetail($transaction) {
        return match ($transaction->payment_type) {
            'package' => $this->extractPackageDetail($transaction?->TransactionDetail?->type_ref_id),
            'project' => $this->extractProjectDetail($transaction?->TransactionDetail?->type_ref_id, $transaction?->TransactionDetail?->transaction_type),
            'gig' => $this->extractGigDetail($transaction?->TransactionDetail?->type_ref_id),
            default => []
        };
    }

    protected function extractPackageDetail($packageId) {
        if (!empty($packageId)) {
            $packageDetail = Package::select('id','title', 'price')->whereKey($packageId)->first();
        }
        return [
            'package_id'    => $packageDetail->id,
            'title'         => $packageDetail->title,
            'package_price' => $packageDetail->price,
            'return_url'    => route('packages'),
            'type'          => 'package'
        ];
    }

    protected function extractGigDetail($gigOrderId) {
        if (!empty($gigOrderId)) {
            $gigOrder = GigOrder::select('id', 'gig_id', 'author_id','plan_type','gig_delivery_days','plan_amount','gig_addons','downloadable')
                        ->with('gig:id,title,slug,author_id')->whereKey($gigOrderId)->first();
        }
        return [
            'gig_addons'            => $gigOrder?->gig_addons,
            'gig_id'                => $gigOrder?->gig->id,
            'plan_id'               => $gigOrder?->plan_id,
            'gig_author'            => $gigOrder?->gig_author,
            'title'                 => $gigOrder?->gig?->title,
            'downloadable'          => $gigOrder?->downloadable,
            'plan_type'             => $gigOrder?->plan_type,
            'plan_price'            => $gigOrder?->plan_amount,
            'delivery_time'         => $gigOrder?->gig_delivery_days,
            'return_url'            => route('freelance.gigs.activity', ['slug' => $gigOrder?->gig?->slug, 'order_id' => $gigOrderId]),
            'type'                  => 'gig'
        ];
    }

    protected function extractProjectDetail($refId, $projectType) {
        if (!empty($projectType)) {
            $projectData = match ($projectType) {
                1 => $this->getProjectMilestoneData($refId),
                2 => $this->getProjectFixedData($refId),
                3 => $this->getProjectHourlyData($refId),
                default => null
            };
        }
        return [
            'project_id'                => $projectData?->project?->id,
            'proposal_id'               => $projectData?->proposal_id,
            'project_type'              => $projectData?->project?->project_type,
            'project_payout_type'       => $projectData?->project?->project_payout_type,
            'timecard_id'               => $projectType == 3 ? $projectData?->id : 0,
            'timecard_title'            => $projectType == 3 ? $projectData?->title : '',
            'timecard_price'            => $projectType == 3 ? $projectData?->price : '',

            'milestone_id'              => $projectType == 1 ? $projectData?->id : 0,
            'milestone_title'           => $projectType == 1 ? $projectData?->title : '',
            'milestone_price'           => $projectType == 1 ? $projectData?->price : '',

            'title'                     => $projectData?->project?->project_title,
            'slug'                      => $projectData?->project?->slug,
            'project_min_price'         => $projectData?->project?->project_min_price,
            'project_max_price'         => $projectData?->project?->project_max_price,
            'proposal_amount'           => $projectData?->proposal?->proposal_amount,
            'transaction_type'          => $projectType, // 1: mile 2: fixed 3: hourly
            'payout_type'               => $projectType == 2 ? $projectData?->payout_type :$projectData?->proposal?->payout_type,
            'return_url'                => route('freelance.projects.activity', ['slug' => $projectData?->project?->slug, 'id'=> $projectData?->proposal_id]),
            'type'                      => 'project'
        ];
    }

    protected function getProjectMilestoneData($milestoneId) {
        return ProposalMilestone::select('id','proposal_id','title', 'price')->with(['project:projects.id,project_title,slug,project_type,project_min_price,project_max_price,project_payout_type', 'proposal:id,proposal_amount,payout_type'])->whereKey($milestoneId)->first();
    }

    protected function getProjectHourlyData($timecardId) {
        return ProposalTimecard::select('id','proposal_id','title', 'price')->with('project:projects.id,project_title,slug,project_type,project_min_price,project_max_price,project_payout_type', 'proposal:id,proposal_amount,payout_type')->whereKey($timecardId)->first();
    }

    protected function getProjectFixedData($proposalId) {
        return Proposal::select('id AS proposal_id','project_id','payout_type', 'proposal_amount')->with('project:projects.id,project_title,slug,project_type,project_min_price,project_max_price')->whereKey($proposalId)->first();
    }
}
