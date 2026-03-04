<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'name',
        'email',
        'amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}