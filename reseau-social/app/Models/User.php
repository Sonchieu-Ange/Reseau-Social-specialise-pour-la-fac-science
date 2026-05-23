<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

// 🟢 On importe le modèle de base MongoDB pour Laravel
use MongoDB\Laravel\Eloquent\Model; 
// 🟢 On importe les types de relations spécifiques à MongoDB
use MongoDB\Laravel\Relations\HasOne;
use MongoDB\Laravel\Relations\HasMany;
use MongoDB\Laravel\Relations\BelongsToMany;

#[Fillable(['name', 'email', 'password', 'role', 'is_active', 'suspended_at', 'suspension_reason', 'suspension_until'])]
#[Hidden(['password', 'remember_token'])]
class User extends Model implements Authenticatable // 🟢 Hérite de MongoDB Model et implémente Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, AuthenticatableTrait; // 🟢 On utilise le Trait d'authentification

    // 🟢 On spécifie explicitement la collection MongoDB
    protected $collection = 'users';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_at' => 'datetime',
            'suspension_until' => 'datetime',
        ];
    }

    /**
     * Relations (Adaptées pour MongoDB)
     */

    /**
     * Obtenir le profil de l'utilisateur
     */
 // Dans ton fichier app/Models/User.php

/**
 * Relation avec le Profil (Un utilisateur a un seul profil)
 */
public function profile()
{
    return $this->hasOne(Profile::class);
}

/**
 * Relation avec les Posts (Un utilisateur a plusieurs publications)
 */
public function posts()
{
    return $this->hasMany(Post::class)->orderBy('created_at', 'desc'); // trié du plus récent au plus ancien
}

    /**
     * Obtenir les commentaires de l'utilisateur
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Obtenir les messages envoyés par l'utilisateur
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Obtenir les messages reçus par l'utilisateur
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Obtenir tous les messages de l'utilisateur (envoyés et reçus)
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id')
            ->union($this->hasMany(Message::class, 'receiver_id')->getQuery());
    }

    /**
     * Obtenir les groupes membres de l'utilisateur
     * (Dans MongoDB, on stocke souvent ça sous forme de tableau "members" dans Group, 
     * mais si tu utilises une collection pivot group_members, cette relation reste valide)
     */
/**
     * Obtenir les groupes membres de l'utilisateur
     */
   public function groups() // 🟢 Suppression du typage strict pour éviter le conflit de driver
    {
        return $this->belongsToMany(Group::class);
    }
    /**
     * Obtenir les groupes présidés par l'utilisateur
     */
    public function presidedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'president_id');
    }

    /**
     * Obtenir les événements organisés par l'utilisateur
     */
    public function organizedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Obtenir les événements auxquels l'utilisateur est inscrit
     */
   public function registeredEvents() // 🟢 Idem ici
    {
        return $this->belongsToMany(Event::class);
    }

    /**
 * Vérifie si l'utilisateur est un administrateur.
 *
 * @return bool
 */
public function isAdmin(): bool
{
    // Adapte la valeur de la chaîne ('admin') en fonction de ce que tu enregistres en BDD
    return $this->role === 'admin';
}
}