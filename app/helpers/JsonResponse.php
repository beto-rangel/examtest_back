<?php

namespace App\helpers;

class JsonResponse implements Response
{

    public static function singleResponse(array $data)
    {
        return \Response::json([
            "success" => true,
            "data"    => $data
        ], 200);
    }


    public static function collectionResponse(
        $collection,
        $per_page = 15,
        $from = 1,
        $current_page = 1,
        $last_page = 1,
        $to = 1
    ) {
        return \Response::json([
            "success" => true,
            "items"   => [
                "per_page"     => $per_page,
                "from"         => $from,
                "data"         => $collection,
                "total"        => is_array($collection) ? count($collection) : $collection->count(),
                "current_page" => $current_page,
                "last_page"    => $last_page,
                "to"           => $to
            ]
        ]);
    }


    public static function errorResponse($message, $status_code)
    {
        return \Response::json([
            "success" => false,
            "error"   => [
                "message" => $message,
                "code"    => $status_code,
            ]
        ], $status_code);
    }
}