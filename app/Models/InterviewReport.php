<?php

namespace App\Models;

use Database\Factories\InterviewReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewReport extends Model
{
    /**
     * @use HasFactory<InterviewReportFactory>
     */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'interview_id',
        'overall_score',
        'strengths',
        'weaknesses',
        'recommendations',
    ];

    protected function casts(): array
    {
        return [
            'overall_score' => 'decimal:2',
            'strengths' => 'array',
            'weaknesses' => 'array',
            'recommendations' => 'array',
        ];
    }

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }
}
