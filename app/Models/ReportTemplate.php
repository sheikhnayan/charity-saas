<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'description',
        'report_type',
        'default_configuration',
        'is_public',
        'created_by'
    ];

    protected $casts = [
        'default_configuration' => 'array',
        'is_public' => 'boolean'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
