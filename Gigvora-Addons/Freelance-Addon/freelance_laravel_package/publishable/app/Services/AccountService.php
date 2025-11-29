<?php 

namespace App\Services;

use App\Models\Gig\Gig;
use App\Models\Gig\GigOrder;
use App\Models\Project;
use App\Models\Proposal\Proposal;
use App\Models\Seller\SellerPayout;
use App\Models\Seller\SellerWithdrawal;
use App\Models\Transaction;
use App\Models\UserBillingDetail;
use App\Models\UserWallet;
use App\Models\UserWalletDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountService {
       public function getSellerAccountStats($request, $wallet, $profileId) {
        $proposals              = Proposal::where('author_id', $profileId)->select('status')->get();
        $gigs                   = Gig::where('author_id', $profileId)->has('gig_orders')->select('id')->with('gig_orders:id,gig_id,status')->get();

        $cancelled_projects = $completed_projects = $ongoing_projects = 0;
        $sold_gigs          = $ongoing_gigs       = $cancelled_gigs   = 0;
        $amounts = 0;
        if(!$proposals->isEmpty()){
            $ongoing_projects = $proposals->filter( function($request){
                return $request->status == 'hired';
            })->count();

            $completed_projects = $proposals->filter( function($request){ 
                return $request->status == 'completed';
            })->count();

            $cancelled_projects = $proposals->filter( function($request){ 
                return in_array($request->status , ['disputed', 'refunded']);
            })->count();
        }

        if(!$gigs->isEmpty()){
            foreach($gigs as $gig){
                $ongoing_gigs += $gig->gig_orders->filter( function($request){ 
                        return  $request->status == 'hired';
                })->count();

                $sold_gigs += $gig->gig_orders->filter( function($request){ 
                    return  $request->status == 'completed';
                })->count();

                $cancelled_gigs += $gig->gig_orders->filter( function($request){ 
                    return  in_array($request->status , ['disputed', 'refunded']);
                })->count();
            }
        }

        $total_earning  = $available_balance = $withdraw_amount = $pending_income = 0;
        
        if( !empty($wallet) ){
            $total_earning      = UserWalletDetail::where('wallet_id', $wallet->id)->sum('amount');
            $available_balance  = $wallet->amount; 
        }

        $withdraw_amount    = SellerWithdrawal::where('seller_id', $profileId)->sum('amount');
        $pending_income     = SellerPayout::whereHas( 'Transaction', function($query){
            $query->select('id')->whereIn('status', array('processed', 'cancelled'));
        })->where('seller_id', $profileId)->sum('seller_amount');

        if(!empty($wallet)){
            $amounts     = UserWalletDetail::where('wallet_id', $wallet->id)
            ->whereMonth('created_at', Carbon::now()->month)->groupBy('month')
            ->select(DB::raw("DATE_FORMAT(created_at,'%d') as month"), DB::raw('sum(amount) as sum'))
            ->pluck('sum','month')->toArray();
        }

        $date_intervals         = range(1, date('d'));
        $price_intervals        = $transaction_values = [];

        foreach($date_intervals as $day ){
            $value = !empty($amounts[sprintf("%02d", $day)]) ? $amounts[sprintf("%02d", $day)] : 0;
            array_push($price_intervals, $value);
        }

        return [
            'total_earning'         => $total_earning ?? 0,
            'available_balance'     => $available_balance ?? 0,
            'withdraw_amount'       => $withdraw_amount ?? 0,
            'pending_income'        => $pending_income ?? 0,
            'cancelled_projects'    => $cancelled_projects ?? 0,
            'completed_projects'    => $completed_projects ?? 0,
            'ongoing_projects'      => $ongoing_projects ?? 0,
            'ongoing_gigs'          => $ongoing_gigs ?? 0,
            'sold_gigs'             => $sold_gigs ?? 0,
            'cancelled_gigs'        => $cancelled_gigs ?? 0,
            'price_intervals'       => $price_intervals ?? 0,
            'date_intervals'        => $date_intervals ?? 0,
            'amounts'               => $amounts ?? 0,
        ];
    }

    public function getBuyerAccountStats($request, $wallet, $profileId) {
        $projects   = Project::where('author_id', $profileId)->select('status')->get();
        $gig_orders = GigOrder::where('author_id', $profileId)->select('status','plan_amount')->get();

        $ongoing_order_amount = $cancelled_projects = $completed_projects = $ongoing_projects = 0;
        if(!$projects->isEmpty()){
            $ongoing_projects = $projects->filter( function($request){
                return $request->status == 'hired';
            })->count();

            $completed_projects = $projects->filter( function($request){ 
                return $request->status == 'completed';
            })->count();

            $cancelled_projects = $projects->filter( function($request){ 
                return in_array($request->status , ['cancelled', 'refunded']);
            })->count();
        } 
       
        if(!$gig_orders->isEmpty()){
            $ongoing_gigs = $gig_orders->filter( function($request){ 
                return  $request->status == 'hired';
            })->count();
        }

        $project_spend_amount   = $ongoing_amount = $gig_spend_amount  = $available_balance = 0;

        if( !empty($wallet) ){
            $available_balance  = $wallet->amount; 
        }
        $transactions = Transaction::where('creator_id', $profileId)->whereIn('status' , ['processed','completed','refunded'])->whereIn('payment_type', ['gig','project'])->select('id','payment_type','status', 'created_at')->with('TransactionDetail:id,transaction_id,amount,used_wallet_amt')->get();
        if( !$transactions->isEmpty() ){
            $project_spend_amount = $transactions->filter( function($request){ 
                return  $request->payment_type == 'project' && $request->status == 'completed';
            })->sum(function ($row) {
                return $row->TransactionDetail->amount + $row->TransactionDetail->used_wallet_amt;
            });
            
            $gig_spend_amount = $transactions->filter( function($request){
                return $request->payment_type == 'gig' && $request->status == 'completed';
            })->sum(function ($row) {
                return $row->TransactionDetail->amount + $row->TransactionDetail->used_wallet_amt;
            });

            $ongoing_amount = $transactions->filter( function($request){
                return $request->status == 'processed';
            })->sum(function ($row) {
                return $row->TransactionDetail->amount + $row->TransactionDetail->used_wallet_amt;
            });

            $transactions_amt = $transactions->filter( function($request){ 
                return  in_array($request->status, ['completed', 'refunded']);
            });

            $date_intervals         = range(1, date('d'));
            $price_intervals        = $transaction_values = [];

            foreach( $transactions_amt as $key => $tran ) {
                $day    = Carbon::parse($tran->created_at)->format('d');
                $day    = intval($day);
                if(!isset($transaction_values[$day]['amount'])){
                    $transaction_values[$day]['amount'] = 0;
                }
                
                if(!isset( $transaction_values[$day][$tran->payment_type] ) ){
                    $transaction_values[$day][$tran->payment_type] = 0;
                }

                $transaction_values[$day][$tran->payment_type]  += $tran->TransactionDetail->amount + $tran->TransactionDetail->used_wallet_amt;
                $transaction_values[$day]['amount']             += $tran->TransactionDetail->amount + $tran->TransactionDetail->used_wallet_amt;
            }

            foreach($date_intervals as $day ){
                $value = !empty($transaction_values[$day]['amount']) ? $transaction_values[$day]['amount'] : 0;
                array_push($price_intervals, $value);
            }
        }
        return [
            'projects'              => $projects->count() ?? 0,
            'gig_orders'            => $gig_orders->count()?? 0,
            'cancelled_projects'    => $cancelled_projects?? 0,
            'completed_projects'    => $completed_projects ?? 0 ,
            'ongoing_projects'      => $ongoing_projects ?? 0 ,
            'ongoing_gigs'          => $ongoing_gigs ?? 0 ,
            'total_earning'         => $project_spend_amount ?? 0 ,
            'ongoing_amount'        => $ongoing_amount ?? 0 ,
            'gig_spend_amount'      => $gig_spend_amount ?? 0 ,
            'available_balance'     => $available_balance ?? 0 ,
            'price_intervals'       => $price_intervals ?? 0 ,
            'date_intervals'        => $date_intervals ?? 0 ,
            'ongoing_order_amount'  => $ongoing_order_amount ?? 0 ,
            'transaction_values'    => $transaction_values ?? 0
        ];
    }

    public function getUserWallet($profileId) {
        return UserWallet::select('id', 'amount')->where('profile_id', $profileId)->first();
    }

    public function getPayoutHistory($filters, $profileId) {
        
        $payouts_history = SellerWithdrawal::where('seller_id', $profileId)->orderBy('id', 'desc');

        if (!empty($filters['status'])) {
            $payouts_history = $payouts_history->where('status', $filters['status']);
        }
        return $payouts_history->paginate($filters['per_page']);
    }

    public function setPayoutMethod($params)
    {
        $user = getUserRole();
        $profile_id = $user['profileId'];
        $billingRec         = UserBillingDetail::where('profile_id', $profile_id )->select('payout_settings')->first();
        $record             = [];
        if(!empty($billingRec)){
            $payout_settings = @unserialize($billingRec->payout_settings);
            $record          = !empty($payout_settings) ? $payout_settings : [];
        }
        
        $record['default_selected']  = $params['type'];

        if( $params['type'] == 'escrow' ){
            $record['escrow'] = [
                'escrow_email' => $params['escrow_email'],
                'escrow_api' => $params['escrow_api_key'],
            ];
        } elseif( $params['type'] == 'paypal' ){
            $record['paypal'] = [
                'paypal_email' => $params['paypal_email'],
            ];
        } elseif( $params['type'] == 'payoneer' ){
            $record['payoneer'] = [
                'payoneer_email' => $params['payoneer_email'],
            ];
        } elseif($params['type'] == 'bank'){
            $record['bank'] = [
                'title'             =>  $params['title'],
                'account_number'    =>  $params['account_number'],
                'bank_name'         =>  $params['bank_name'],
                'routing_number'    =>  $params['routing_number'],
                'bank_iban'         =>  $params['bank_iban'],
                'bank_bic_swift'    =>  $params['bank_bic_swift'],
            ];
        }

        $serializeData = serialize($record);
        $data['payout_settings'] = $serializeData;
        
        return UserBillingDetail::select('id')
        ->updateOrCreate([ 'profile_id'  => $profile_id ], $data);
    }

    public function getPayoutMethod() {
        $profile_id = Auth::id();
        $billingRec = UserBillingDetail::where('profile_id', $profile_id)->select('payout_settings')->first();
        $record = [];
        if ($billingRec) {
            $payout_settings = @unserialize($billingRec->payout_settings);
            if ($payout_settings !== false) {
                $record = $payout_settings;
            }
        }
        return $record;
    }

    public function withdrawAmount($amount, $payout_type)
    {
        $user = getUserRole();
        $profile_id = $user['profileId'];
        $wallet = UserWallet::select('id', 'profile_id', 'amount')->where('profile_id', $profile_id)->first();
        
        if( !empty($wallet) && $wallet->amount > 0 ){
            $billing_info = UserBillingDetail::select('payout_settings')->where('profile_id', $profile_id)->first();

            if( empty($billing_info) || empty($billing_info->payout_settings ) ){
                return [
                    'type'      => 'payout_error',
                    'message'   => __('transaction.payout_setting_error')
                ]; 
            }

            $payouts_settings = unserialize( $billing_info->payout_settings );
            if( empty($payouts_settings[$payout_type]) ){
                return [
                    'type'      => 'payout_error',
                    'message'   => __('transaction.payout_setting_error')
                ]; 
            }

            DB::beginTransaction();
            try{

                SellerWithdrawal::create([
                    'seller_id'        => $profile_id,
                    'amount'            => $amount,
                    'payment_method'    => $payout_type,
                    'detail'            => serialize($payouts_settings[$payout_type]),
                ]);

                if( !empty($billing_info) ){
                    $payout_settings                = @unserialize($billing_info->payout_settings);
                    $record                         = !empty($payout_settings) ? $payout_settings : [];
                    $record['default_selected']     = $payout_type;
                    $data['payout_settings']        = serialize($record);
                    UserBillingDetail::select('id')->updateOrCreate(
                        [ 'profile_id'  => $profile_id ], $data
                    );
                }

                $wallet_amount = $wallet->amount - $amount;
                $wallet->update(['amount' => $wallet_amount]);
                DB::commit();
                return [
                    'type'      => 'success',
                    'message'   => __('general.funds_withdraw_request')
                ];
            }catch(\Exception $e) {
                DB::rollback();
                return [
                    'type'      => 'success',
                    'message'   => $e->getMessage()
                ];
            }
        }

        return [
            'type' => 'error',
            'message' => __('general.wallet_emtpy')
        ];
        
    }
}

