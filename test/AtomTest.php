<?php
/**
 */


namespace IronSourceAtom;
require_once '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class AtomTest extends TestCase
{

    /**
     * @covers Atom::__construct
     * @expectedException InvalidArgumentException
     */
    public function testAtomConstructNullAuth()
    {
        new Atom(null);
    }


}