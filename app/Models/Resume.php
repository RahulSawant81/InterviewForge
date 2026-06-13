<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'original_filename',
        'stored_filename',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
