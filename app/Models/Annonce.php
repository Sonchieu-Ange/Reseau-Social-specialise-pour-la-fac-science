<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;

class Annonce extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'annonces';

    protected $fillable = ['titre', 'contenu', 'createur_id'];

    protected $casts = [
        'date_publication' => 'datetime',
    ];

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'createur_id');
    }
}