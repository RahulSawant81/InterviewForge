<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\DifficultyLevel;
use App\Enums\InterviewStatus;
use App\Enums\InterviewType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interview extends Model
{
    use HasFactory, SoftDeletes;
    use softDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'technologies',
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
