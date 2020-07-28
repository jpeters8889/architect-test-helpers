<?php

namespace JPeters\Architect\TestHelpers\Laravel\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = [];
}
