<?php


namespace IronSourceAtom;


class AtomTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventNullStream()
    {
        $atom = new Atom("dd");
        $atom->putEvent(null, 'fff');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventEmptyStream()
    {
        $atom = new Atom("dd");
        $atom->putEvent('', 'fff');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventNullData()
    {
        $atom = new Atom("dd");
        $atom->putEvent('fff', null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventEmptyData()
    {
        $atom = new Atom("dd");
        $atom->putEvent('fff', '');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventsNullStream()
    {
        $atom = new Atom("dd");
        $atom->putEvents(null, 'fff');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventsEmptyStream()
    {
        $atom = new Atom("dd");
        $atom->putEvents('', 'fff');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventsNullData()
    {
        $atom = new Atom("dd");
        $atom->putEvents('fff', null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventsEmptyData()
    {
        $atom = new Atom("dd");
        $atom->putEvents('fff', '');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPutEventsNotValidArray()
    {
        $atom = new Atom("dd");
        $atom->putEvents('fff', 'ffff');
    }


}