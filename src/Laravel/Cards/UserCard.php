<?php

declare(strict_types=1);

namespace JPeters\Architect\TestHelpers\Laravel\Cards;

use JPeters\Architect\Cards\Card;

class UserCard extends Card
{
    public function vueComponent(): string
    {
        return 'user-card';
    }
}
