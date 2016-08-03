<?php
/**
 * Created by IntelliJ IDEA.
 * User: kirill.bokhanov
 * Date: 8/1/16
 * Time: 3:35 PM
 */

namespace IronSourceAtom;

require 'Atom.php';
require 'EventWorker.php';
require 'DbAdapter.php';

class Tracker
{
    private $taskWorkersCount = 20;
    private $taskPoolSize = 10000;
    private $url;
    private $authKey = '';
    private $streams;
    private $flush_now;
    private $eventPool;
    private $eventWorker;
    private $dbAdapter;

    /**
     * +     * Tracker constructor.
     * +     * @param string $url
     * +     */
    public function __construct($url = "http://track.atom-data.io/")
    {
        $this->url = $url;
        $this->streams = array();
        $this->eventWorker = new EventWorker($this->streams);
        $this->dbAdapter = new DbAdapter();
        $this->dbAdapter->create();

    }

    /**
     * +     * @param int $bulkSizeByte
     * +     */
    public function setBulkSizeByte($bulkSizeByte)
    {
        $this->eventWorker->setBulkSizeByte($bulkSizeByte);
    }

    /**
     * @param int $bulkSize
     */
    public function setBulkSize($bulkSize)
    {
        $this->eventWorker->setBulkSize($bulkSize);
    }

    /**
     * @param int $taskWorkersCount
     */
    public function setTaskWorkersCount($taskWorkersCount)
    {
        $this->taskWorkersCount = $taskWorkersCount;
    }

    /**
     * @param int $taskPoolSize
     */
    public function setTaskPoolSize($taskPoolSize)
    {
        $this->taskPoolSize = $taskPoolSize;
    }

    /**
     * @param int $flushInterval
     */
    public function setFlushInterval($flushInterval)
    {
        $this->eventWorker->setFlushInterval($flushInterval);
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $authKey
     */
    public function setAuthKey($authKey)
    {
        $this->authKey = $authKey;
    }

    /**
     * @param $data
     * @param $stream
     */
    public function track($data, $stream)
    {
//        if (!array_key_exists($stream, $this->streams)) {
//            $this->streams[$stream] = array($data);
//        } else {
//            array_push($this->streams[$stream], $data);
//        }
//
//        print ("tracker called");
//        var_export($this->streams);
        $this->dbAdapter->addEvent($stream, $data);
    }

    /**
     *
     */
    public function flush()
    {
        $this->flush_now = true;
    }

}

$tracker = new Tracker();
$tracker->track("first message", "stream1");
$tracker->track("second message", "stream1");
$tracker->track("third message", "stream1");
$tracker->track("first message", "stream2");
$tracker->track("fourth message", "stream1");
$tracker->track("second message", "stream2");
$tracker->track("first message", "stream3");