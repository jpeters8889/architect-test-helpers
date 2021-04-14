<?php

declare(strict_types=1);

namespace JPeters\Architect\TestHelpers\Laravel;

use Illuminate\Contracts\Auth\Authenticatable;

class ArchitectGateway implements \JPeters\Architect\ArchitectGateway
{
    public function canUseArchitect(Authenticatable $user): bool
    {
        return $user->email === 'jamie@jamie-peters.co.uk';
    }
}
