<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Session;

class Badge extends Model
{
    use HasFactory;

    public const REVIEW_APPROVED = 'approved';
    public const REVIEW_UNDER_REVIEW = 'under_review';
    public const REVIEW_REJECTED = 'rejected';

    protected $table = 'batchs';

    protected $casts = [
        'eligibility_snapshot' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isActive(): bool
    {
        if (($this->review_status ?? self::REVIEW_APPROVED) !== self::REVIEW_APPROVED) {
            return false;
        }

        if ((int) ($this->status ?? 0) !== 1) {
            return false;
        }

        $start = $this->start_date ? Carbon::parse($this->start_date) : null;
        $end = $this->end_date ? Carbon::parse($this->end_date) : null;

        return $start && $end && now()->between($start, $end);
    }

    public static function add_payment_success($identifier, $transaction_keys = array())
    {
        $payment_details = session('payment_details');
        $snapshot = session('gigvora_verify_snapshot', []);

        $transaction_keys = json_encode($transaction_keys);
        $data['status'] = 1;
        $data['icon'] = 'fa-circle-check';
        $data['start_date'] = $payment_details['custom_field']['start_date'];
        $dateString = $payment_details['custom_field']['end_date'];
        $newTimestamp = strtotime($dateString);
        $newDateString = date('Y-m-d H:i:s', $newTimestamp);
        $data['end_date'] = $newDateString;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['user_id'] = auth()->user()->id;
        $data['title'] = $payment_details['items'][0]['title'];
        $data['description'] = $payment_details['items'][0]['subtitle'];
        $data['review_status'] = config('gigvora_verify.default_review_status', self::REVIEW_UNDER_REVIEW);
        $data['eligibility_snapshot'] = $snapshot;
        DB::table('batchs')->updateOrInsert(['user_id' => $data['user_id']], $data);

        $payment_data['item_type'] = 'badge';

        $payment_data['item_id'] = $payment_details['items'][0]['id'];
        $payment_data['user_id'] = auth()->user()->id;
        $payment_data['amount'] = $payment_details['payable_amount'];
        $payment_data['identifier'] = $identifier;
        $payment_data['transaction_keys'] = $transaction_keys;
        $payment_data['currency'] = get_settings('system_currency');
        $payment_data['created_at'] = date('Y-m-d H:i:s');
        $payment_data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('payment_histories')->insert($payment_data);

        session(['payment_details' => []]);
        session()->forget('gigvora_verify_snapshot');

        auth()->user()->forceFill([
            'profile_locked_for_verification' => true,
            'profile_lock_reason' => 'gigvora_verify_review',
        ])->save();

        Session::flash('success_message', get_phrase('Thanks! Your Gigvora Verify review has started.'));
        return redirect()->route('badge');
    }
}
