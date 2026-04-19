<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;

class GroupeMembre extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'groupe_membres';

    protected $fillable = ['groupe_id', 'utilisateur_id', 'role'];

    protected $casts = [
        'date_adhesion' => 'datetime',
    ];

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class);
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }
}