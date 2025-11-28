<?php

namespace App\Services;

use App\Models\Dispute;
use App\Models\Transaction;

class TransactionsService
{


    public function getInvoices(int $per_page = 20)
    {
        $user = getUserRole();
        $profile_id = $user['profileId']; 
        $role_id = $user['roleId']; 

        $query = Transaction::select('id', 'creator_id', 'status', 'payment_type', 'created_at')
            ->withWhereHas('TransactionDetail:id,transaction_id,amount,used_wallet_amt');

        if ($role_id == 'buyer') {
            $query->where('creator_id', $profile_id);
        } elseif ($role_id == 'seller') {
            $query->with('sellerPayout:id,transaction_id')
                ->where(function ($query) use ($profile_id) {
                    $query->whereHas('sellerPayout', function ($subQuery) use ($profile_id) {
                        $subQuery->where('seller_id', $profile_id);
                    })->orWhere('creator_id', $profile_id);
                });
        }

        return $query->orderBy('id', 'desc')->paginate($per_page);
    }

}
