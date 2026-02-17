<?php

namespace Apiura\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavedApiRequest extends Model
{
    protected $fillable = [
        'user_id',
        'module_id',
        'name',
        'priority',
        'team',
        'method',
        'path',
        'path_params',
        'query_params',
        'headers',
        'body',
        'response_status',
        'response_headers',
        'response_body',
    ];

    protected $casts = [
        'path_params' => 'array',
        'query_params' => 'array',
        'headers' => 'array',
        'body' => 'array',
        'response_headers' => 'array',
    ];

    /**
     * Get the user that owns the saved request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('apiura.user_model', 'App\\Models\\User'));
    }

    /**
     * Get the module this request belongs to.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(ApiuraModule::class, 'module_id');
    }

    /**
     * Get the comments for the saved request.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(SavedApiRequestComment::class);
    }
}
