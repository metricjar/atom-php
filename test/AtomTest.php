<?php
/**
 */


namespace IronSourceAtom;


class AtomTest extends \PHPUnit_Framework_TestCase
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