<?php
/**
 * Created by IntelliJ IDEA.
 * User: kirill.bokhanov
 * Date: 8/1/16
 * Time: 3:35 PM
 */

namespace IronSourceAtom;

require 'Atom.php';
require 'DbAdapter.php';
require 'Logger.php';

class Tracker
{
    private $taskWorkersCount = 20;
    private $taskPoolSize = 10000;
    private $flush_now;
    private $dbAdapter;
    private $bulkSizeByte = 65536;
    private $bulkSize = 4;
    private $flushInterval = 10;
    private $isDebug = false;


    /**
     * +     * Tracker constructor.
     * +     * @param string $url
     * +     */
    public function __construct($auth = "", $url = "http://track.atom-data.io/")
    {
        $this->atom = new Atom($auth, $url);
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
        $this->atom->setUrl($url);
    }

    /**
     * @param string $authKey
     */
    public function setAuthKey($authKey)
    {
        $this->atom->setAuthKey($authKey);
    }

    /**
     * @param $data
     * @param $stream
     */
    public function track($data, $stream, $authKey="")
    {
        if (empty($authKey)) {
            $authKey = $this->atom->getAuthKey();
        }

        $this->dbAdapter->addEvent($stream, $data, $authKey);

        if ($this->isToFlush($stream)) {
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

        Logger::log("Flushing into stream " . $stream . " data: " . $data, $this->isDebug);

        $result = $this->atom->putEvents($stream, $data);

        Logger::log("Response is: " . $result->message, $this->isDebug);
        if ($result->code < 500) {
            $this->dbAdapter->deleteEvents($stream, $batch->getLastId());
            $byteSize = $this->dbAdapter->getByteSize($stream);
            $byteSize -= $batch->getByteSize();
            $this->dbAdapter->updateByteSize($stream, $byteSize);
         }
    }

    private function isToFlush($stream)
    {
        if ($this->dbAdapter->getByteSize($stream) >= $this->bulkSizeByte) {
            return true;
        }

        if ($this->dbAdapter->countEvents($stream) >= $this->bulkSize) {
            return true;
        }
        return false;
    }

    /**
     * @param boolean $isDebug
     */
    public function setDebug($isDebug)
    {
        $this->isDebug = $isDebug;
    }

}

$tracker = new Tracker();
$tracker->setAuthKey('I40iwPPOsG3dfWX30labriCg9HqMfL');
$tracker->setDebug(true);
$tracker->track("first message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("second message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("third message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("first message", "stream2");
$tracker->track("fourth message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("fifth message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("second message", "stream2");
$tracker->track("first message", "stream3");
$tracker->track("sixth message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("eighth message", "sdkdev_sdkdev.public.atomtestkey");
$tracker->track("ninth message", "sdkdev_sdkdev.public.atomtestkey");

