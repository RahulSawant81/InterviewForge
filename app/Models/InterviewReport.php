<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewReport extends Model
{
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

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}
