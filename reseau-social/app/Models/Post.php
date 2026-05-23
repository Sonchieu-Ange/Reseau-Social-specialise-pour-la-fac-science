<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// ❌ RETIRE l'ancien modèle SQL :
// use Illuminate\Database\Eloquent\Model;

// 🟢 IMPORTE le modèle de base MongoDB pour Laravel
use MongoDB\Laravel\Eloquent\Model;
// 🟢 IMPORTE les relations NoSQL spécifiques
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    // 🟢 On déclare explicitement la collection MongoDB pour les publications
    protected $collection = 'posts';

    protected $fillable = [
        'user_id',
        'content',
        'media',
        'likes',
        'is_reported',
    ];

    protected $casts = [
        'media' => 'array',
        'likes' => 'array',
        'is_reported' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtenir l'utilisateur qui a créé ce post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir les commentaires de ce post
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Obtenir le nombre de likes
     */
    public function getLikesCountAttribute(): int
    {
        return count($this->likes ?? []);
    }

    /**
     * Vérifier si l'utilisateur a aimé le post
     */
    public function isLikedByUser($userId): bool
    {
        // On convertit l'ID en chaîne de caractères pour s'assurer de la correspondance textuelle dans le tableau BSON
        return in_array((string)$userId, array_map('strval', $this->likes ?? []));
    }
}