<?php

namespace IronSourceAtom;


class Response
{
public $message;
public $code;

    /**
     * Response constructor.
     * @param $message 
     * @param $code
     */
    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

}