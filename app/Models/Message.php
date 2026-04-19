<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;

class Message extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'messages';

    protected $fillable = ['expediteur_id', 'destinataire_id', 'contenu'];

    protected $casts = [
        'date_envoi' => 'datetime',
    ];

    public function expediteur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'expediteur_id');
    }

    public function destinataire(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'destinataire_id');
    }
}