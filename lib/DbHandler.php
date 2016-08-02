<?php
/**
 * Created by IntelliJ IDEA.
 * User: kirill.bokhanov
 * Date: 8/2/16
 * Time: 5:15 PM
 */

namespace IronSourceAtom;


class DbHandler extends \SQLite3
{
    function __construct()
    {
        $this->open('ironsourceatom.db');
    }

}