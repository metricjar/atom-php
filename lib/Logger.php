<?php
/**
 * Created by IntelliJ IDEA.
 * User: kirill.bokhanov
 * Date: 8/4/16
 * Time: 3:25 PM
 */

namespace IronSourceAtom;


class Logger
{
    public static function log($message, $isDebug){

        if($isDebug){
            print($message. "\r\n");
        }
}

}