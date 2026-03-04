<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'photo',
        'description',
        'website_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the website that owns the teacher
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Get all users assigned to this teacher
     */
    public function students()
    {
        return $this->hasMany(User::class, 'teacher_id');
    }

    /**
     * Scope to get only active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
