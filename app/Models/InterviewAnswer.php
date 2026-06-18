<?php

namespace App\Models;

use Database\Factories\InterviewAnswerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read InterviewQuestion|null $question
 */
class InterviewAnswer extends Model
{
    /**
     * @use HasFactory<InterviewAnswerFactory>
     */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'interview_question_id',
        'answer',
        'score',
        'feedback',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<InterviewQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(InterviewQuestion::class, 'interview_question_id');
    }
}
