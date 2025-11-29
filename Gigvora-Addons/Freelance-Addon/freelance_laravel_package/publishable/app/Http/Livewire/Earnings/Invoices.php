<?php

namespace App\Http\Livewire\Earnings;

use Amentotech\LaraPayEase\Facades\PaymentDriver;
use Livewire\Component;
use App\Models\Transaction;
use App\Models\SellerPayout;
use App\Models\TransactionDetail;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use App\Services\PaymentService;
class Invoices extends Component
{

    use WithPagination;

    public $per_page  = '';
    public $profile_id, $userRole;
    public $date_format         = '';
    public $currency_symbol     = '';
    public $className           = '';
    protected $listeners = ['deleteInvoice', 'rePay'];

    public function mount( $className='' ){
        $this->className = $className;
        $date_format            = setting('_general.date_format');
        $currency               = setting('_general.currency');
        $per_page_record        = setting('_general.per_page_record');
        $this->date_format      = !empty($date_format)          ? $date_format : 'm d, Y';
        $this->per_page         = !empty( $per_page_record )    ? $per_page_record : 10;
        $currency_detail        = !empty( $currency)        ? currencyList($currency) : array();
        if( !empty($currency_detail['symbol']) ){
            $this->currency_symbol = $currency_detail['symbol'];
        }

        $user = getUserRole();
        $this->profile_id       = $user['profileId'];
        $this->userRole         = $user['roleName'];
    }

    public function render(){

        $invoices = Transaction::select('id', 'creator_id', 'payment_type', 'payment_method', 'status', 'created_at')
        ->withWhereHas(
            'TransactionDetail:id,transaction_id,amount,used_wallet_amt',
        )->when( $this->userRole == 'buyer', function ($query) {
            return $query->where('creator_id', $this->profile_id);
        })->when( $this->userRole == 'seller', function ($subQuery) {
            return $subQuery->with('sellerPayout:id,transaction_id')->where( function ($query) {
                $query->whereHas('sellerPayout', function ($chil_query) {
                    $chil_query->where('seller_id', $this->profile_id);
                })->orWhere('creator_id', $this->profile_id);
            });
        });

        $invoices   = $invoices->orderBy('id', 'desc');
        $invoices   = $invoices->paginate($this->per_page);
        $sitInfo    = getSiteInfo();
        $siteTitle  = $sitInfo['site_name'];
        $title      = $siteTitle . ' | ' . __('general.invoices');

        return view('livewire.earnings.invoices', compact('invoices'))->extends('layouts.app', compact('title'));
    }

    public function deleteInvoice( $params ){
        $transaction = Transaction::find($params['id']);
        if( !empty($transaction) && $transaction->status == 'pending' ){
            $transaction->delete();
            TransactionDetail::where('transaction_id', $params['id'])->delete();
            $this->resetPage();
        } else {
            $this->dispatchBrowserEvent('showAlertMessage', [
                'type'      => 'error',
                'title'     => __('general.error_title'),
                'message'   => __('project.project_delete_error')
            ]);
            return;
        }
    }

    public function rePay($params) {
        $transaction = Transaction::where('id', $params['id'])->with('TransactionDetail')->first();
        if(!empty($transaction)) {
            $paymentService  =   new PaymentService();
            $data = $paymentService->getTransactionItemDetail($transaction);
            if($data['type'] == 'package') {
                $package_data = [
                    'creator_id'        => $this->profile_id,
                    'package_id'        => $data['package_id'],
                    'package_title'     => $data['title'],
                    'package_price'     => $data['package_price'],
                    'return_url'        => $data['return_url']
                ];
                session()->forget('project_data');
                session()->forget('gig_data');
                session()->put(['package_data' => $package_data ]);
            } elseif ($data['type'] == 'gig') {
                $gig_data = [
                    'creator_id'        => $this->profile_id,
                    'gig_id'            => $data['gig_id'],
                    'gig_title'         => $data['title'],
                    'gig_author'        => $data['gig_author'],
                    'plan_id'           => $data['plan_id'],
                    'plan_type'         => $data['plan_type'],
                    'delivery_time'     => $data['delivery_time'],
                    'plan_price'        => $data['plan_price'],
                    'gig_addons'        => $data['gig_addons'],
                    'downloadable'      => $data['downloadable'],
                    'return_url'        => $data['return_url']
                ];

                session()->forget('package_data');
                session()->forget('project_data');
                session()->put(['gig_data' => $gig_data ]);
            } elseif ($data['type'] == 'project') {
                $project_data = [
                    'creator_id'        => $this->profile_id,
                    'project_id'        => $data['project_id'],
                    'proposal_id'       => $data['proposal_id'],
                    'project_title'     => $data['title'],
                    'project_slug'      => $data['slug'],
                    'payout_type'       => $data['payout_type'],
                ];
                if($data['transaction_type'] == 1) {
                    // Milestone
                    $project_data['milestone_id']       = $data['milestone_id'];
                    $project_data['milestone_title']    = $data['milestone_title'];
                    $project_data['milestone_price']    = $data['milestone_price'];

                } elseif ($data['transaction_type'] == 2) {
                    // Fixed
                    $project_data['proposal_amount']    = $data['proposal_amount'];
                    $project_data['project_type']       = $data['project_type'];
                    $project_data['project_min_price']  = $data['project_min_price'];
                    $project_data['project_max_price']  = $data['project_max_price'];
                }
                elseif ($data['transaction_type'] == 3) {
                    // Hourly
                    $project_data['timecard_id']        = $data['timecard_id'];
                    $project_data['timecard_title']     = $data['timecard_title'];
                    $project_data['timecard_price']     = $data['timecard_price'];
                }

                session()->forget('package_data');
                session()->forget('gig_data');
                session()->put(['project_data' => $project_data ]);
            }

            $ipnUrl = PaymentDriver::getIpnUrl($transaction->payment_method);

            session()->put([
                'payment_data'  => [
                    'amount'        => $transaction->transactionDetail->amount,
                    'title'         => $data['title'],
                    'description'   => __('transaction.transaction_desc'),
                    'ipn_url'       => !empty($ipnUrl) ? route($ipnUrl , ['payment_method' => $transaction->payment_method]) : url('/'),
                    'order_id'      => $transaction->id,
                    'track'         => Str::random(36),
                    'cancel_url'    => route('invoice.cancel'),
                    'success_url'   => route('dashboard'),
                    'email'         => $transaction->transactionDetail->payer_email,
                    'name'          => $transaction->transactionDetail->payer_first_name,
                    'payment_type'  => 'package',
                ]
            ]);
            return redirect()->route('payment.process', ['gateway' => $transaction->payment_method]);
        }
    }
}
