<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;

class EvenementParticipant extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'evenement_participants';

    protected $fillable = ['evenement_id', 'utilisateur_id'];

    protected $casts = [
        'inscrit_le' => 'datetime',
    ];

    public function evenement(): BelongsTo
    {
        return $this->belongsTo(Evenement::class);
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }
}