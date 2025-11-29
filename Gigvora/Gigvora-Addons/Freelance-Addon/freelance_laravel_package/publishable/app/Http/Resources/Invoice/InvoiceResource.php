<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'type'      => ucwords(__('general.invoice_type', ['type' => $this->payment_type])),
            'amount'    => priceFormat($this->TransactionDetail->amount),
            'data'      => date(setting('_general.date_format') ?? 'm d, Y', strtotime($this->created_at)),
            'status'    => $this->status
        ];
    }
}
