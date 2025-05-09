<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable  implements JWTSubject
{
    // use  HasApiTokens,;
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];


    // Esse método retorna o identificador do usuário para o token JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Retorna a chave primária do usuário
    }

    // Esse método permite adicionar reivindicações personalizadas no token JWT
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Define o relacionamento de um usuário com muitos posts (1:N).
    // public function posts()
    // {
    //     return $this->hasMany(Post::class);
    // }
}
