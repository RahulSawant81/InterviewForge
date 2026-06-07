<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'profile_image',
        'headline',
        'experience_years',
        'current_company',
        'current_ctc',
        'expected_ctc',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
