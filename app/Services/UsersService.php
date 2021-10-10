<?php

namespace App\Services;

use App\Abstracts\Service;
use App\Models\User;
use App\Override\Models\Model;

class UsersService extends Service
{
    /**
     * @return Model
     */
    public function getDefaultModel(): Model
    {
        return new User();
    }
}
