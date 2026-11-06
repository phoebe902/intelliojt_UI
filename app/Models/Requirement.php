<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requirement extends Model
{
    public const PHASES = [
        'before' => 'Before OJT',
        'during' => 'During OJT duty',
        'after'  => 'After OJT duty',
    ];

    public const TYPES = [
        'document'      => 'Document (submitted by the student)',
        'participation' => 'Participation (confirmed by the coordinator)',
        'received'      => 'Received from the HTE (recorded by the coordinator)',
    ];

    public const RECURRENCES = [
        'none'    => 'One-time',
        'monthly' => 'Every month',
        'weekly'  => 'Every week',
    ];

    protected $fillable = [
        'name', 'description', 'phase', 'type', 'recurrence', 'due_on', 'is_required', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['due_on' => 'date', 'is_required' => 'boolean'];
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(RequirementSubmission::class);
    }
}
