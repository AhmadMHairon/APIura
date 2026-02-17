<?php

namespace Apiura\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedApiRequestComment extends Model
{
    protected $fillable = [
        'saved_api_request_id',
        'user_id',
        'author_name',
        'author_type',
        'content',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the saved API request that owns the comment.
     */
    public function savedApiRequest(): BelongsTo
    {
        return $this->belongsTo(SavedApiRequest::class);
    }

    /**
     * Get the user that created the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('apiura.user_model', 'App\\Models\\User'));
    }

    /**
     * Get the display name for the comment author.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        return $this->author_name ?? 'Anonymous';
    }
}
