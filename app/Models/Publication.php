<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\HasMany;
use MongoDB\Laravel\Relations\BelongsToMany;

class Publication extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'publications';

    protected $fillable = ['auteur_id', 'contenu', 'type_media', 'media_url'];

    protected $casts = [
        'date_publication' => 'datetime',
    ];

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'auteur_id');
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(Utilisateur::class, null, 'publication_ids', 'utilisateur_ids');
    }
}