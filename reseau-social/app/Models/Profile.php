<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// ❌ RETIRE cette ligne :
// use Illuminate\Database\Eloquent\Model;
// ❌ RETIRE cette ligne :
// use Illuminate\Database\Eloquent\Relations\BelongsTo;

// 🟢 AJOUTE le modèle de base MongoDB pour Laravel
use MongoDB\Laravel\Eloquent\Model;
// 🟢 AJOUTE la relation BelongsTo spécifique à MongoDB
use MongoDB\Laravel\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    // 🟢 Spécifie explicitement la collection MongoDB pour les profils
    protected $collection = 'profiles';

    protected $fillable = [
        'user_id',
        'bio',
        'department',
        'level',
        'phone',
        'address',
        'profile_picture',
    ];

    /**
     * Obtenir l'utilisateur auquel appartient ce profil
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}