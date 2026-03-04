<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketSellDetail extends Model
{
    public function ticket(){
        return $this->belongsTo(Ticket::class);
    }
    
    public function ticketSell(){
        return $this->belongsTo(TicektSell::class, 'ticket_sell_id');
    }
}
