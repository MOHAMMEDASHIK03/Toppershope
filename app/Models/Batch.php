<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Batch extends Model
{

    protected $fillable = [
        'uuid',
        'course_id','category_id','subcategory_id','name','price','original_price',
        'total_seats','filled_seats','start_date','status',
        'mode','schedule','mentor_name','mentor_photo',
        'mentor_designation','batch_description','is_upcoming','language','duration_weeks',
    ];

    protected $casts = [
        'is_upcoming' => 'boolean',
        'start_date'  => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Batch $batch) {
            if (empty($batch->uuid)) {
                $batch->uuid = (string) Str::uuid();
            }
        });

        static::saving(function (Batch $batch) {
            if (empty($batch->uuid)) {
                $batch->uuid = (string) Str::uuid();
            }
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /** Computed: seats remaining */
    public function getSeatsRemainingAttribute()
    {
        return max(0, $this->total_seats - $this->filled_seats);
    }

    /** Computed: fill percentage */
    public function getFillPercentAttribute()
    {
        if ($this->total_seats <= 0) return 0;
        return min(100, round(($this->filled_seats / $this->total_seats) * 100));
    }
}

