<?php

namespace App\Models;

use App\Models\Traits\HasImageSrcAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory,
        HasImageSrcAttribute;

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'image',
        'age',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
