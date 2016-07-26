<?php
/**
 */


namespace IronSourceAtom;

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