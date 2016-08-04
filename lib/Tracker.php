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
    private $dbAdapter;
    private $bulkSizeByte = 65536;
    private $bulkSize = 4;
    private $flushInterval = 10;

    /**
     * +     * Tracker constructor.
     * +     * @param string $url
     * +     */
    public function __construct($url = "http://track.atom-data.io/")
    {   $this->atom = new Atom('I40iwPPOsG3dfWX30labriCg9HqMfL');
        $this->url = $url;
        $this->streams = array();
        $this->eventWorker = new EventWorker($this->streams);
        $this->dbAdapter = new DbAdapter();
        $this->dbAdapter->create();

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

        while ($this->isToFlush($stream)) {
            $this->flush($stream);
        }
    }

    /**
     *
     */
    public function flush($stream)
    {
        $this->flush_now = true;
        $batch = $this->dbAdapter->getEvents($stream, $this->bulkSize);
        $data = json_encode($batch->getEvents());
        $this->atom->putEvents($stream, $data);
        print ("I flush stream " . $stream);
        $this->dbAdapter->deleteEvents($stream, $batch->getLastId());
    }

    private function isToFlush($stream)
    {
        if ($this->dbAdapter->countEvents($stream) >= $this->bulkSize) {
            return true;
        }
        return false;
    }

}

$tracker = new Tracker();
$tracker->track("first message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("second message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("third message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("first message", "stream2");
$tracker->track("fourth message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("fifth message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("second message", "stream2");
$tracker->track("third message", "stream2");
$tracker->track("first message", "stream3");
$tracker->track("sixth message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("eighth message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("ninth message", "sdkdev_sdkdev.public.atomtestkey");

