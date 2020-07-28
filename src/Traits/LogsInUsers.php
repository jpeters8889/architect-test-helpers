<?php

namespace JPeters\Architect\TestHelpers\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use JPeters\Architect\TestHelpers\Laravel\Models\User;

trait LogsInUsers
{
    protected function logIn(Authenticatable $user = null)
    {
        if (!$user) {
            $user = factory(User::class)->create();
        }

        $this->actingAs($user, 'api');

        $this->withHeader('Authorization', 'Bearer: '.$user->api_token);
    }
}
