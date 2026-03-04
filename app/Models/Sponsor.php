<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    public function website()
    {
        return $this->belongsTo(Website::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
