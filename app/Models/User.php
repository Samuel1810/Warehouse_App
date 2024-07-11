<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function movements() {
        return $this->hasMany(Movement::class);
    }

    public function materials() {
        return $this->belongsToMany(Material::class, 'user_materials')->withPivot('quantidade');
    }

    public function ownedMaterials()
    {
        return $this->belongsToMany(Material::class);
    }

    // Relacionamento com PurchaseMaterial (como proprietário)
    public function purchaseMaterials()
    {
        return $this->hasMany(PurchaseMaterial::class, 'utilizador_id');
    }

    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_projects');
    }

    // Função de definição de utilizador (TRUE OR FALSE)
    public function isAdmin()
    {
        return $this->user_type === 1;
    }

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
