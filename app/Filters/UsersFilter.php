<?php

namespace App\Filters;

use App\Abstracts\QueryFilter;

class UsersFilter extends QueryFilter
{
    public function id(int $id): void
    {
        $this->getBuilder()->where('id', $id);
    }
}
