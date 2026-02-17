<?php

namespace Apiura\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedApiFlow extends Model
{
    protected $fillable = [
        'module_id',
        'name',
        'description',
        'steps',
        'default_headers',
        'continue_on_error',
    ];

    protected $casts = [
        'steps' => 'array',
        'default_headers' => 'array',
        'continue_on_error' => 'boolean',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(ApiuraModule::class, 'module_id');
    }
}
