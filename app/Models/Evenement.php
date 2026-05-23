<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\HasMany;

class Evenement extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'evenements';

    protected $fillable = ['titre', 'description', 'date_debut', 'date_fin', 'lieu', 'createur_id'];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
        'cree_le'    => 'datetime',
    ];

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'createur_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EvenementParticipant::class, 'evenement_id');
    }
}