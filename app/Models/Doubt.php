<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doubt extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 
        'batch_id', 
        'user_id', 
        'subject', 
        'description', 
        'image_path', 
        'is_resolved', 
        'faculty_reply'
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];

    public function replies()
    {
        return $this->hasMany(DoubtReply::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
