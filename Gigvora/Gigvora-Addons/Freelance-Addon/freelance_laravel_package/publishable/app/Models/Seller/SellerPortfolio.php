<?php

namespace App\Models\Seller;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class SellerPortfolio extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected function attachments(): Attribute {
        return Attribute::make(
            get: function ($value) {
                if(!empty($value)){
                    $record = @unserialize($value);
                    if($record != 'b:0;' || $record !== false){
                        return !empty($record['files']) ? array_values($record['files']) : [];
                    }
                }
                return [];
            },
            set: fn ($value) => $value ?? null,
        );
    }

    public function portfolioAuthor()
    {
        return $this->belongsTo(Profile::class, 'author_id', 'id');
    }
}
