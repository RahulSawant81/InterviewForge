<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewAnswer extends Model
{
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

    public function question(): belongsTo
    {
        return $this->belongsTo(
            InterviewQuestion::class,
            'interview_question_id'
        );
    }
}
