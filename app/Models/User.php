<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'email',
        'password',
        'phone',
        'dob',
        'target_exam',
        'grade_category',
        'category_id',
        'subcategory_id',
        'district',
        'state',
        'role',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function admissionContact()
    {
        return $this->hasOne(\App\Models\Admission\AdmissionContact::class, 'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    /**
     * Role Checkers
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFacultyHead(): bool
    {
        return $this->role === 'faculty_head';
    }

    public function isFaculty(): bool
    {
        return $this->role === 'faculty' || $this->role === 'faculty_head';
    }

    /**
     * The courses this specific faculty member is assigned to manage
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
