<?php
/**
 */


namespace IronSourceAtom;


class AtomTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAtomConstructNullAuth()
    {
        new Atom(null);
    }


}