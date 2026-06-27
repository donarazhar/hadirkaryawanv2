<?php

namespace App\Models;

use Laravel\Passport\Client as PassportClient;
use Illuminate\Contracts\Auth\Authenticatable;

class Client extends PassportClient
{
    /**
     * Determine if the client should skip the authorization prompt.
     * Karena ini untuk SSO Internal (Persuratan), kita otomatis menyetujui tanpa prompt.
     */
    public function skipsAuthorization(Authenticatable $user, array $scopes): bool
    {
        return true;
    }
}
