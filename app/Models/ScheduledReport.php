<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'description',
        'report_type',
        'configuration',
        'frequency',
        'format',
        'recipients',
        'is_active',
        'last_run_at',
        'next_run_at'
    ];

    protected $casts = [
        'configuration' => 'array',
        'recipients' => 'array',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function executions()
    {
        return $this->hasMany(ReportExecution::class);
    }
}
