<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enum\DifficultyLevel;
use App\Enum\InterviewStatus;
use App\Enum\InterviewType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Interview extends Model
{
    use softDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'technology',
        'difficulty',
        'status',
        'total_questions',
        'started_at',
        'completed_at'
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'type' => InterviewType::class,
            'status' => InterviewStatus::class,
            'difficulty' => DifficultyLevel::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function questions(): hasMany
    {
        return $this->hasMany(InterviewQuestion::class)
            ->orderBy('sequence');
    }

    public function report(): hasOne
    {
        return $this->hasOne(InterviewReport::class);
    }

}
