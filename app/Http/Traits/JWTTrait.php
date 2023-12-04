<?php

namespace App\Http\Traits;

use App\User;
use JWTAuth;

trait JWTTrait
{

    /**
     * @return \Tymon\JWTAuth\JWTAuth
     */
    private function parse()
    {
        return JWTAuth::parseToken();
    }


    /**
     * @return User
     */
    private function getCurrentUser()
    {
        return $this->parse()->authenticate();
    }


    /**
     * @return \Tymon\JWTAuth\Payload
     */
    private function getPayload()
    {
        return $this->parse()->getPayload();
    }


    /**
     * @param $key
     *
     * @return mixed
     */
    public function getFromPayload($key)
    {
        return $this->parse()->getPayload()->get($key);
    }
}