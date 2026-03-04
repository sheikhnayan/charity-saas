<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_id', 'website_id', 'amount', 'type', 'name', 'last_name', 'email',
        'address', 'apartment', 'city', 'state', 'zip', 'phone', 'country', 'ip_address',
        'fee', 'fee_paid', 'status', 'reference_id', 'name_on_card', 'tip_amount', 'tip_percentage', 'payment_method'
    ];
    
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class,'reference_id','id');
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class,'reference_id','id');
    }

    public function ticket()
    {
        return $this->belongsTo(TicektSell::class,'reference_id','id');
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class,'reference_id','id');
    }
}
