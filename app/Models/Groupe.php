<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\HasMany;

class Groupe extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'groupes';

    protected $fillable = ['nom', 'description', 'communaute_id', 'createur_id'];

    protected $casts = [
        'cree_le' => 'datetime',
    ];

    public function communaute(): BelongsTo
    {
        return $this->belongsTo(Communaute::class, 'communaute_id');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'createur_id');
    }

    public function membres(): HasMany
    {
        return $this->hasMany(GroupeMembre::class, 'groupe_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MessageGroupe::class, 'groupe_id');
    }

    public function estMembre($utilisateurId): bool
    {
        return $this->membres()->where('utilisateur_id', $utilisateurId)->exists();
    }

    public function estAdmin($utilisateurId): bool
    {
        return $this->membres()
            ->where('utilisateur_id', $utilisateurId)
            ->where('role', 'admin')
            ->exists();
    }
}