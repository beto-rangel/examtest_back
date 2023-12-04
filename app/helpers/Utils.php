<?php

namespace App\helpers;


class Utils
{
    public static function isEmpty($object){
        if(gettype($object) == 'array'){
            return COUNT($object) == 0;
        }

        if(gettype($object) == 'object'){
            return !COUNT(get_object_vars($object));
        }

        return !isset($object);
    }

    public static function toArray($object){
        if(gettype($object) == 'object'){
            return get_object_vars($object);
        }

        return $object;
    }

    public static function arrayExtend($arr1, $arr2) {
        foreach($arr2 as $key => $val) {
            if(array_key_exists($key, $arr1) && is_array($val)) {
                $arr1[$key] = array_extend($arr1[$key], $arr2[$key]);
            } else {
                $arr1[$key] = $val;
            }
        }
        return $arr1;
    }
}