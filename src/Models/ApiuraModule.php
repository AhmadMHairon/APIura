<?php

namespace Apiura\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiuraModule extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    public function savedRequests(): HasMany
    {
        return $this->hasMany(SavedApiRequest::class, 'module_id');
    }

    public function savedFlows(): HasMany
    {
        return $this->hasMany(SavedApiFlow::class, 'module_id');
    }

    public function getAncestorsAttribute(): array
    {
        $ancestors = [];
        $current = $this->parent;
        while ($current) {
            array_unshift($ancestors, $current);
            $current = $current->parent;
        }

        return $ancestors;
    }

    public function getDepthAttribute(): int
    {
        return count($this->ancestors);
    }

    public function getTotalItemCountAttribute(): int
    {
        $count = $this->savedRequests()->count() + $this->savedFlows()->count();
        foreach ($this->children as $child) {
            $count += $child->total_item_count;
        }

        return $count;
    }

    /**
     * Check if a given module is a descendant of this module.
     */
    public function isDescendantOf(int $moduleId): bool
    {
        $current = $this->parent;
        while ($current) {
            if ($current->id === $moduleId) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }
}
