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

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventNullStream()
    {
        $atom = new Atom('');
        $atom->putEvent(null, 'fff');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventEmptyStream()
    {
        $atom = new Atom('');
        $atom->putEvent('', 'fff');
    }


}