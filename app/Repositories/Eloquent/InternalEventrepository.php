<?php

namespace App\Repositories\Eloquent;

use App\Entities\InternalEvent;

class InternalEventRepository extends EloquentRepository
{
    public function getModel()
    {
        return new InternalEvent();
    }
}