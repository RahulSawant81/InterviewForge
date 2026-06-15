<?php

namespace App\Models;

use App\Enums\DifficultyLevel;
use App\Enums\InterviewStatus;
use App\Enums\InterviewType;
use Database\Factories\InterviewFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property InterviewType $type
 * @property DifficultyLevel $difficulty
 * @property InterviewStatus $status
 * @property array<int, string> $technologies
 * @property-read User $user
 * @property-read Collection<int, InterviewQuestion> $questions
 * @property-read InterviewReport|null $report
 */
class Interview extends Model
{
    /** @use HasFactory<InterviewFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'technologies',
        'difficulty',
        'status',
        'total_questions',
        'started_at',
        'completed_at',
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

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<InterviewQuestion, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(InterviewQuestion::class)
            ->orderBy('sequence');
    }

    /**
     * @return HasOne<InterviewReport, $this>
     */
    public function report(): HasOne
    {
        return $this->hasOne(InterviewReport::class);
    }
}
