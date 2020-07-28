<?php

namespace JPeters\Architect\TestHelpers\Laravel;

class Mix extends \Illuminate\Foundation\Mix
{
    public function __invoke($path, $manifestDirectory = '')
    {
        return parent::__invoke($path, '');
    }
}
