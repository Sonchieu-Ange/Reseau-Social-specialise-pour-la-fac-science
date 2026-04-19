<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;

class Commentaire extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'commentaires';

    protected $fillable = ['publication_id', 'auteur_id', 'contenu'];

    protected $casts = [
        'date_commentaire' => 'datetime',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'auteur_id');
    }
}