<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\HasMany;

class Communaute extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'communautes';

    protected $fillable = ['nom', 'description', 'createur_id'];

    protected $casts = [
        'cree_le' => 'datetime',
    ];

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'createur_id');
    }

    public function groupes(): HasMany
    {
        return $this->hasMany(Groupe::class, 'communaute_id');
    }
}