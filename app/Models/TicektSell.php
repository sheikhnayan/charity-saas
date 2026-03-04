<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicektSell extends Model
{
    public function details()
    {
        return $this->hasMany(TicketSellDetail::class,"ticket_sell_id","id");
    }
}
