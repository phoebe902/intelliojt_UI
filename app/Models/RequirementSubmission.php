<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequirementSubmission extends Model
{
    protected $fillable = [
        'requirement_id', 'student_id', 'period', 'status', 'file_path',
        'submitted_at', 'reviewed_at', 'reviewed_by', 'remarks',
    ];

    protected function casts(): array
    {
        return ['submitted_at' => 'datetime', 'reviewed_at' => 'datetime'];
    }

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(Requirement::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}