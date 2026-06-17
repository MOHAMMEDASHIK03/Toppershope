<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

    protected $fillable = [
        'name','slug','description','about','category_id','subcategory_id','thumbnail',
        'language','duration',
        'what_you_learn','highlights','includes','syllabus','faqs',
        'faculty','hero_image',
        'meta_title','meta_description','is_published',
    ];

    protected $casts = [
        'what_you_learn' => 'array',
        'highlights'     => 'array',
        'includes'       => 'array',
        'syllabus'       => 'array',
        'faqs'           => 'array',
        'faculty'        => 'array',
        'is_published'   => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * The actual users (faculty role) who are assigned to manage this course's content
     */
    public function faculty_users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Curriculum Hierarchy
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class)->orderBy('order');
    }

    public function doubts()
    {
        return $this->hasMany(Doubt::class);
    }
}

