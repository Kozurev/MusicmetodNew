<?php

namespace App\Override\Providers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Str;

class AuthUserProvider implements UserProvider
{
    /**
     * @var User
     */
    private User $user;

    /**
     * AuthUserProvider constructor.
     * @param User $user
     */
    public function __construct (User $user)
    {
        $this->user = $user;
    }

    /**
     * @param mixed $identifier
     * @return Authenticatable|null
     */
    public function retrieveById($identifier): ?Authenticatable
    {
        return $this->user->find($identifier);
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return User|Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return User::where('auth_token', $token);
    }

    /**
     * @param Authenticatable $user
     * @param string $token
     */
    public function updateRememberToken(Authenticatable $user, $token) {}

    /**
     * @param array $credentials
     * @return User|Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = $this->user;
        foreach ($credentials as $credentialKey => $credentialValue) {
            if (!Str::contains($credentialKey, 'password')) {
                $user->where($credentialKey, $credentialValue);
            }
        }

        return $user->first();
    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return password_verify($credentials['password'], $user->getAuthPassword());
    }
}
