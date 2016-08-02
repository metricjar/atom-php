<?php
/**
 * Created by IntelliJ IDEA.
 * User: kirill.bokhanov
 * Date: 8/1/16
 * Time: 3:51 PM
 */

namespace IronSourceAtom;


class EventWorker

{

    private $bulkSizeByte = 65536;
    private $bulkSize = 4;
    private $flushInterval = 10;
    private $atom;
    private $streams;

    public function __construct($streams)
    {
        $this->atom = new Atom();
        $this->streams = $streams;
    }

    public function run()
    {

        while (true) {

        }

    }

    /**
     * @param int $bulkSizeByte
     */
    public function setBulkSizeByte($bulkSizeByte)
    {
        $this->bulkSizeByte = $bulkSizeByte;
    }

    /**
     * @param int $bulkSize
     */
    public function setBulkSize($bulkSize)
    {
        $this->bulkSize = $bulkSize;
    }

    /**
     * @param int $flushInterval
     */
    public function setFlushInterval($flushInterval)
    {
        $this->flushInterval = $flushInterval;
    }

    /**
     * @param $stream
     * @param $data
     */
    private function flushData($stream, $data)
    {
        $this->atom->putEvents($stream, $data);
    }


}