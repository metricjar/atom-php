<?php

namespace IronSourceAtom;

require 'Atom.php';
require 'DbAdapter.php';
require 'Logger.php';

/**
 * Class Tracker high level API class, support track() and flush() methods
 * Aggregates, stores and sends data to IronSourceAtom data pipeline
 * @package IronSourceAtom
 */
class Tracker
{

    private $dbAdapter;
    private $bulkSizeByte = 65536;//64 kB
    private $bulkSize = 4;
    private $flushInterval = 10000;//10 seconds
    private $isDebug = false;


    /**
     * Tracker constructor.
     * @param string $url
     * @param string $authKey
     */
    public function __construct($authKey = "", $url = "http://track.atom-data.io/")
    {
        $this->atom = new Atom($authKey, $url);
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
     * @param string $stream the name of IronSourceAtom stream to send data
     * @param string $data data in JSON format to be send.
     * @param string $authKey optional, pre shared IronSourceAtom stream auth key.
     * If nothing given uses authKey given in constructor
     */
    public function track($stream, $data, $authKey = "")
    {
        if (empty($authKey)) {
            $authKey = $this->atom->getAuthKey();
        }

        $this->dbAdapter->addEvent($stream, $data, $authKey);

        if ($this->isToFlush($stream)) {
            $this->flushStream($stream, $authKey);
        }
    }

    /**
     * @param string $stream
     * @param string $authKey
     */
    private function flushStream($stream, $authKey)
    {
        $batch = $this->dbAdapter->getEvents($stream, $this->bulkSize);
        $data = json_encode($batch->getEvents());

        Logger::log("Data to flush: " . $data, $this->isDebug);

        $result = $this->atom->putEvents($stream, $data, $authKey);

        Logger::log("Response is: " . $result->message, $this->isDebug);
        if ($result->code < 500) {
            $this->dbAdapter->deleteEvents($stream, $batch->getLastId());
            $byteSize = $this->dbAdapter->getByteSize($stream);
            $byteSize -= $batch->getByteSize();
            $this->dbAdapter->updateByteSize($stream, $byteSize);
        }
    }

    /**
     * @param string $stream
     * @return bool
     */
    private function isToFlush($stream)
    {
        if ($this->dbAdapter->getByteSize($stream) >= $this->bulkSizeByte) {
            Logger::log("\nFlushing by bulkSizeByte into stream: " . $stream, $this->isDebug);
            return true;
        }

        if ($this->dbAdapter->countEvents($stream) >= $this->bulkSize) {
            Logger::log("\nFlushing by bulkSize into stream: " . $stream, $this->isDebug);
            return true;
        }

        if ($this->dbAdapter->milliseconds() - $this->dbAdapter->getOldestCreationTime($stream) >= $this->flushInterval) {
            Logger::log("\nFlushing by timer into stream: " . $stream, $this->isDebug);
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

    /**
     * Flush all data buffer to server immediately
     */
    public function flush()
    {
        $stream = $this->dbAdapter->getStreamsInfo();
        foreach ($stream as $entity) {
            if ($this->dbAdapter->countEvents($entity->streamName) > 0) {
                Logger::log("\nFlushing by client demand into stream: " . $entity->streamName, $this->isDebug);
                $this->flushStream($entity->streamName, $entity->authKey);
            }
        }

    }

}


