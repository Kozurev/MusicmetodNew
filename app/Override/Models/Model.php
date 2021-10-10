<?php

namespace App\Override\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel
{
    use Filterable;
}
