<?php

declare(strict_types=1);

namespace JPeters\Architect\TestHelpers\Laravel\Blueprints;

use JPeters\Architect\Cards\Card;
use JPeters\Architect\Plans\DateTime;
use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\TestHelpers\Laravel\Cards\UserCard;

class User extends Blueprint
{
    public function model(): string
    {
        return \JPeters\Architect\TestHelpers\Laravel\Models\User::class;
    }

    public function plans(): array
    {
        return [
            new Textfield('name'),

            new Textfield('email'),

            new Textfield('password'),

            (new Textfield('api_token'))->hideFromIndexOnMobile(),

            (new DateTime('created_at'))->hideOnForms(),

            (new DateTime('updated_at'))->hideOnIndex()->hideOnForms(),
        ];
    }

    public function card(): ?string
    {
        return UserCard::class;
    }
}
