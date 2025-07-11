<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormOption extends Model
{
    protected $fillable = [
        'category',
        'key', 
        'value',
        'city_slug',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get options by category
     */
    public static function getByCategory(string $category, ?string $citySlug = null): array
    {
        $query = static::where('category', $category)
            ->where('is_active', true);
            
        if ($citySlug) {
            $query->where('city_slug', $citySlug);
        }
        
        return $query->orderBy('sort_order')
            ->orderBy('value')
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Get options values only by category
     */
    public static function getValuesByCategory(string $category, ?string $citySlug = null): array
    {
        $query = static::where('category', $category)
            ->where('is_active', true);
            
        if ($citySlug) {
            $query->where('city_slug', $citySlug);
        }
        
        return $query->orderBy('sort_order')
            ->orderBy('value')
            ->pluck('value')
            ->toArray();
    }
}
