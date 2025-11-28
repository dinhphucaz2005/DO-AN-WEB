<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meme extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'image_data',
        'mime_type',
        'description',
        'is_public',
        'data',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy(User $user): bool // Added this method
    {
        return $this->likes->contains('user_id', $user->id);
    }

    // Quan hệ với Comment
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
