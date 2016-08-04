<?php
/**
 * Created by IntelliJ IDEA.
 * User: kirill.bokhanov
 * Date: 8/4/16
 * Time: 3:54 PM
 */

namespace IronSourceAtom;


class Response
{
public $message;
public $code;

    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

}