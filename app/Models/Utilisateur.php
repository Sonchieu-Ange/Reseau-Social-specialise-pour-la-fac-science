<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Hash;
use MongoDB\Laravel\Relations\BelongsToMany;
use MongoDB\Laravel\Relations\HasMany;

class Utilisateur extends Eloquent implements AuthenticatableContract
{
    use Authenticatable;

    protected $connection = 'mongodb';
    protected $collection = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role',
        'filiere',
        'departement',
        'competences',
        'centres_interet',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
    ];

    // Mutateur pour hacher automatiquement le mot de passe
    public function setMotDePasseAttribute($value)
    {
        $this->attributes['mot_de_passe'] = Hash::make($value);
    }

    // Relations

    public function communautesCrees(): HasMany
    {
        return $this->hasMany(Communaute::class, 'createur_id');
    }

    public function groupesCrees(): HasMany
    {
        return $this->hasMany(Groupe::class, 'createur_id');
    }

    public function groupes(): BelongsToMany
    {
        return $this->belongsToMany(Groupe::class, null, 'utilisateur_ids', 'groupe_ids');
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, 'auteur_id');
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class, 'auteur_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'utilisateur_id');
    }

    public function evenementsCrees(): HasMany
    {
        return $this->hasMany(Evenement::class, 'createur_id');
    }

    public function evenementsParticipe(): BelongsToMany
    {
        return $this->belongsToMany(Evenement::class, null, 'utilisateur_ids', 'evenement_ids')
                    ->withTimestamps('inscrit_le');
    }

    public function messagesEnvoyes(): HasMany
    {
        return $this->hasMany(Message::class, 'expediteur_id');
    }

    public function messagesRecus(): HasMany
    {
        return $this->hasMany(Message::class, 'destinataire_id');
    }

    public function annonces(): HasMany
    {
        return $this->hasMany(Annonce::class, 'createur_id');
    }

    // Méthode requise pour l'authentification
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}