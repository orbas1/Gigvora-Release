<?php

namespace App\Http\Livewire\Admin\Settings;

use Amentotech\LaraPayEase\Facades\PaymentDriver;
use Livewire\Component;
use App\Models\Setting\SiteSetting;

class PaymentMethods extends Component
{

    public $currencies = [];

    public $methods = [
        'escrow' => [
            'status' => 'off',
            'email' => '',
            'api_key' => '',
            'currency' => '',
            'inspection_period' => '',
            'fees_payer' => '',
            'api_url' => 'https://api.escrow.com',
        ],
    ];

    public $site_currency = '';
    public $method_type = '';
    public $edit_method = '';

    public function getSiteSettings()
    {
        $data = [];
        $settings = SiteSetting::select('meta_key','meta_value')->where(['setting_type'=> 'payment', 'meta_key' => 'payment_methods'])->first();
        if( !empty($settings) && !empty($settings->meta_value) ){
            $data = unserialize($settings->meta_value);
        }
        return $data;
    }

    public function getSettings(){
        $payment_methods = $this->getSiteSettings();
        $this->method_type = !empty($payment_methods['method_type']) ? $payment_methods['method_type'] : '';

        if(!empty($payment_methods)){
            unset($payment_methods['method_type']);

            foreach($payment_methods as $type => $value){
                if($type == 'escrow'){
                    $this->methods['escrow'] = $value;
                } else {
                    foreach($value as $key => $options){
                        if(!empty($this->methods[$key])){
                            $data = [];
                            foreach($this->methods[$key] as $optkey => $value){
                               $data[$optkey] =  $options[$optkey] ?? $value;
                            }
                            $this->methods[$key] = $data;
                        }
                    }
                }
            }
        }
    }

    public function saveMethod()
    {
        $payment_methods = $this->getSiteSettings();
        $payment_methods['method_type'] = $this->method_type;
        $update = $this->updateSiteSettings($payment_methods);

        if($update){
            $this->showAlertDispatch('success', __('general.success_message'));
            $this->edit_method      = '';
        }else{
            $this->showAlertDispatch('error', __('general.wrong_msg'));
        }
    }

    public function updateStatus($method)
    {
        if(array_key_exists($method, $this->methods)){
            $payment_methods = $this->getSiteSettings();
            $this->methods[$method]['status'] = $this->methods[$method]['status'] == 'on' ? 'off' : 'on' ;
            $payment_methods['others'][$method] = $this->methods[$method];
            $update = $this->updateSiteSettings($payment_methods);

            if($update){
                $this->showAlertDispatch('success', __('general.success_message'));
                $this->edit_method      = '';
            }else{
                $this->showAlertDispatch('error', __('general.wrong_msg'));
            }
        }
    }

    public function editMethod($key)
    {
        $this->edit_method = $key;
        $this->dispatchBrowserEvent('editMethod', );
    }

    public function updateSetting()
    {
        $validations = [];
        foreach($this->methods[$this->edit_method] as $key => $value){
            if( in_array($key, ['status','webhook_url','enable_test_mode', 'exchange_rate' ]))
                continue;
            $validations['methods.'.$this->edit_method.'.'.$key] = 'required';
        }
        $this->validate($validations,['required' => __('general.required_field') ]);

        $payment_methods = $this->getSiteSettings();

        if($this->edit_method == 'escrow'){
            $payment_methods['escrow'] = $this->methods[$this->edit_method];
            $payment_methods['method_type'] = 'escrow';
        } else {
            $payment_methods['method_type'] = 'others';
            if($this->site_currency == $this->methods[$this->edit_method]['currency'] ){
                $this->methods[$this->edit_method]['exchange_rate'] = '';
            }
            $payment_methods['others'][$this->edit_method] = $this->methods[$this->edit_method];
        }

        $update = $this->updateSiteSettings($payment_methods);

        if($update){
            $this->showAlertDispatch('success', __('general.success_message'));
            $this->edit_method      = '';
        }else{
            $this->showAlertDispatch('error', __('general.wrong_msg'));
        }
    }

    public function updateSiteSettings($data)
    {
        return SiteSetting::select('id')->updateOrCreate(
            ['setting_type'=> 'payment','meta_key' => 'payment_methods'],
            ['setting_type'=> 'payment','meta_key' => 'payment_methods','meta_value' => serialize($data)]
        );
    }

    public function rearrangeArray($array) {
        return array_map(function($details) {
            if (isset($details['keys'])) {
                $details = array_merge($details, $details['keys']);
                unset($details['keys']);
            }
            if (isset($details['ipn_url_type'])) {
                unset($details['ipn_url_type']);
            }
            return $details;
        }, $array);
    }

    public function mount()
    {
        $this->site_currency = setting('_general.currency');
        $this->currencies = PaymentDriver::supportedCurrencies();
        $gateways = $this->rearrangeArray(PaymentDriver::supportedGateways());
        $this->methods    = array_merge($this->methods, $gateways);
        $this->getSettings();
    }

    public function render()
    {
        $currency_opt               = currencyOptionForPayment();
        $inspection_day_opt         = inspectionPeriodOptions();

        $fee_paid_by_opt = [
           'seller' => __('settings.fee_paid_by_seller_opt'),
           'buyer'  => __('settings.fee_paid_by_buyer_opt'),
           'both'   => __('settings.fee_paid_by_both_opt'),
        ];

        return view('livewire.admin.payment.payment-methods', compact('currency_opt','inspection_day_opt','fee_paid_by_opt',))->extends('layouts.admin.app');
    }

    public function showAlertDispatch($type, $message)
    {
        $eventData = [];
        if($type == 'success'){
            $eventData['title']     = __('general.success_title');
            $eventData['message']   = $message;
            $eventData['type']      = 'success';
        } else {
            $eventData['title']     = __('general.error_title');
            $eventData['message']   = $message;
            $eventData['type']      = 'error';
        }

        $this->dispatchBrowserEvent('showAlertMessage', $eventData);
    }
}
