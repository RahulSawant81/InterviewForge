<?php

namespace App\Models;

use Database\Factories\InterviewQuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewQuestion extends Model
{
    /**
     * @use HasFactory<InterviewQuestionFactory>
     */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'interview_id',
        'question',
        'question_type',
        'sequence',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function answer(): HasOne
    {
        return $this->hasOne(InterviewAnswer::class);
    }
}
