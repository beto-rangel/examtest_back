<?php

namespace App\Repositories\Eloquent;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends EloquentRepository
{

    /**
     * @return Model
     */
    public function getModel()
    {
        return new User();
    }



}
