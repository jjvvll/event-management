<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

trait CanLoadRelationships{
    public function loadRelationships(
        Model|Builder $for
    ){

    }
}
