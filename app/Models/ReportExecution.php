<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheduled_report_id',
        'status',
        'started_at',
        'completed_at',
        'file_path',
        'file_size',
        'execution_data',
        'error_message',
        'email_sent'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'file_size' => 'integer',
        'execution_data' => 'array',
        'email_sent' => 'boolean'
    ];

    public function scheduledReport()
    {
        return $this->belongsTo(ScheduledReport::class);
    }
}
