<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;

class MessageGroupe extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'messages_groupe';

    protected $fillable = ['groupe_id', 'auteur_id', 'contenu'];

    protected $casts = [
        'cree_le' => 'datetime',
    ];

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class);
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'auteur_id');
    }
}