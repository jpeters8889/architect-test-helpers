<?php

namespace JPeters\Architect\TestHelpers\Laravel\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use JPeters\Architect\Traits\HasArchitectSettings;

class User extends Authenticatable
{
    use HasArchitectSettings;

    protected $guarded = [];
}
