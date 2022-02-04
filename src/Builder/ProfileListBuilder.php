<?php

declare(strict_types=1);

namespace App\Builder;

class ProfileListBuilder
{
    /**
     * @return string[]
     */
    public function getUnswipedProfiles(): array
    {
        return ['foo', 'bar'];
    }
}
