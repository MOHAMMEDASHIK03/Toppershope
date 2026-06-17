<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{

    protected $fillable = [
        'name',
        'slug',
        'description',
        'landing_meta',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'landing_meta' => 'array',
        'is_active' => 'boolean',
    ];

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class)->orderBy('sort_order');
    }

    public function activeSubcategories(): HasMany
    {
        return $this->subcategories()->where('is_active', true);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function landing(string $key, mixed $default = null): mixed
    {
        return data_get($this->landing_meta, $key, $default);
    }
}
