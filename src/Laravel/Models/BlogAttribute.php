<?php

declare(strict_types=1);

namespace JPeters\Architect\TestHelpers\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

class BlogAttribute extends Model
{
    protected $guarded = [];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
