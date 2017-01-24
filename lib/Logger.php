<?php

namespace IronSourceAtom;

class Logger {
    /**
     * @param string $message message to print in log
     * @param boolean $isDebug 
     */
    public static function log($message, $isDebug) {
        if ($isDebug) {
            print($message. "\r\n");
        }
    }
}