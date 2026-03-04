<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'sender_name',
        'sender_email',
        'message',
        'ip_address'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship to User (Student)
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
