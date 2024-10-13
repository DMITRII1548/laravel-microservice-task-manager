<?php

namespace App\Models;

use App\State\Task\Traits\HasState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory,
        HasState;

    protected $fillable = [
        'title',
        'content',
        'started_at',
        'finished_at',
        'status',
        'tags',
        'user_id',
    ];

    protected static function booted()
    {
        parent::boot();

        static::retrieved(function ($task): void {
            $task->state = $task->getCurrentState();
        });
    }

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
