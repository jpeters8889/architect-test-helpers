<?php

declare(strict_types=1);

namespace JPeters\Architect\TestHelpers\Laravel\Models;

use JPeters\Architect\Traits\HasArchitectSettings;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasArchitectSettings;

    protected $guarded = [];
}
