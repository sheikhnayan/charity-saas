<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'role',
        'group_id',
        'group_name',
        'website_id',
        'status',
        'teacher_id',
        'parent_id',
        'password_reset_code',
        'password_reset_expires',
        'email_verified_at',
        'goal',
        'tshirt_size',
        'description'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_reset_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function donations()
    {
        return $this->hasMany(Donation::class)->where('status', 1);
    }

    /**
     * Roles relation (scoped by website via pivot column website_id)
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user_website')
            ->withPivot('website_id')
            ->withTimestamps();
    }

    /**
     * Assign a role to user for a specific website (null for global)
     */
    public function assignRoleForWebsite($roleName, $websiteId = null)
    {
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $role = Role::create(['name' => $roleName, 'label' => ucfirst($roleName)]);
        }

        // Avoid duplicate
        $exists = \DB::table('role_user_website')
            ->where('role_id', $role->id)
            ->where('user_id', $this->id)
            ->where('website_id', $websiteId)
            ->exists();

        if (!$exists) {
            \DB::table('role_user_website')->insert([
                'role_id' => $role->id,
                'user_id' => $this->id,
                'website_id' => $websiteId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Sync roles for a given website scope. Removes existing role assignments for that website and inserts the provided ones.
     * @param array $roleNames
     * @param int|null $websiteId
     * @return void
     */
    public function syncRolesForWebsite(array $roleNames, $websiteId = null)
    {
        // Delete existing assignments for this user + website scope
        \DB::table('role_user_website')
            ->where('user_id', $this->id)
            ->where(function($q) use ($websiteId) {
                if (is_null($websiteId)) {
                    $q->whereNull('website_id');
                } else {
                    $q->where('website_id', $websiteId);
                }
            })
            ->delete();

        foreach ($roleNames as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                $role = Role::create(['name' => $roleName, 'label' => ucfirst($roleName)]);
            }

            \DB::table('role_user_website')->insert([
                'role_id' => $role->id,
                'user_id' => $this->id,
                'website_id' => $websiteId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Check if user has a role for a website (or globally)
     */
    public function hasRoleForWebsite($roleName, $websiteId = null)
    {
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            return false;
        }

        // Check global role
        $global = \DB::table('role_user_website')
            ->where('role_id', $role->id)
            ->where('user_id', $this->id)
            ->whereNull('website_id')
            ->exists();

        if ($global) {
            return true;
        }

        if ($websiteId) {
            return \DB::table('role_user_website')
                ->where('role_id', $role->id)
                ->where('user_id', $this->id)
                ->where('website_id', $websiteId)
                ->exists();
        }

        return false;
    }

    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id', 'id');
    }

    public function setting()
    {
        return $this->hasOne(Setting::class);
    }

    public function investorProfile()
    {
        return $this->hasOne(UserInvestorProfile::class);
    }

    /**
     * Get the teacher assigned to this user
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Get the parent user (for individuals registered by parents)
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get all individuals managed by this parent
     */
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }
}
