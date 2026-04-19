<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\BelongsToMany;

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

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Utilisateur::class, null, 'evenement_ids', 'utilisateur_ids')
                    ->withTimestamps('inscrit_le');
    }
}