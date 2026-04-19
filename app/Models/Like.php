<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;

class Like extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'likes';

    protected $fillable = ['publication_id', 'utilisateur_id'];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }
}