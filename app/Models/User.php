<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Random\RandomException;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'second_name',
        'birthdate',
        'biography',
        'city',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'birthdate' => 'date',
    ];

    public function tokens(): HasMany
    {
        return $this->hasMany(ApiToken::class);
    }

    /**
     * @throws RandomException
     */
    public function createToken(string $name, ?DateTime $expiresAt = null): ApiToken
    {
        $token = bin2hex(random_bytes(32));
        
        return $this->tokens()->create([
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
    }
}
