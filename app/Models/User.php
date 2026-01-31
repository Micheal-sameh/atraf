<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'membership_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function fatherSchedules()
    {
        return $this->hasMany(FatherSchedule::class, 'father_id');
    }

    public function atrafAsFather()
    {
        return $this->hasMany(Etraf::class, 'father_id');
    }

    public function atrafAsUser()
    {
        return $this->hasMany(Etraf::class, 'user_id');
    }

    // Father-User relationships
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'father_users', 'father_id', 'user_id')
            ->withTimestamps();
    }

    public function fathers()
    {
        return $this->belongsToMany(User::class, 'father_users', 'user_id', 'father_id')
            ->withTimestamps();
    }

    // Family helper method
    public function getFamilyCode()
    {
        if (preg_match('/^(E\d+C\d+F\d+)/', $this->membership_code, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
