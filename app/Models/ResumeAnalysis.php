<?php

namespace App\Models;

use Database\Factories\ResumeAnalysisFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeAnalysis extends Model
{
    /**
     * @use HasFactory<ResumeAnalysisFactory>
     */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'resume_id',
        'summary',
        'overall_score',
        'skills',
        'strengths',
        'weaknesses',
        'recommendations',
        'missing_skills',
    ];

    protected function casts(): array
    {
        return [

            'overall_score' => 'decimal:2',
            'skills' => 'array',
            'strengths' => 'array',
            'weaknesses' => 'array',
            'recommendations' => 'array',
            'missing_skills' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Resume, $this>
     */
    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}
