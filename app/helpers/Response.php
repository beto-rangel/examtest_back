<?php

namespace App\helpers;


use Illuminate\Support\Collection;

interface Response

{

    /**
     * Single Response (HTTP/1.1 STATUS CODES 2xx)
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function singleResponse(array $data);


    /**
     * Collection Response (HTTP/1.1 STATUS CODES 2xx)
     *
     * @param Collection|array $collection
     * @param int              $per_page
     * @param int              $from
     * @param int              $current_page
     * @param int              $last_page
     * @param int              $to
     *
     * @return mixed
     */
    public static function collectionResponse(
        $collection,
        $per_page = 15,
        $from = 1,
        $current_page = 1,
        $last_page = 1,
        $to = 1
    );


    /**
     * Errors (HTTP/1.1 STATUS CODES 4xx & 5xx)
     *
     * @param $message
     * @param $status_code
     *
     * @return mixed
     */
    public static function errorResponse($message, $status_code);

}