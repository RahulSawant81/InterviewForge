<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InterviewQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'interview_id',
        'question',
        'question_type',
        'sequence',
    ];

    public function interview(): belongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function answer(): hasOne
    {
        return $this->hasOne(InterviewAnswer::class);
    }
}
